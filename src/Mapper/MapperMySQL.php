<?php
declare(strict_types=1);

namespace App\Mapper;


use App\Entity\Attribute\AttributeInterface;
use App\Entity\Attribute\Storage\Primary;
use App\Entity\Attribute\Storage\StorageInterface;
use App\Entity\Entity;
use App\Entity\Property\Property;
use Dibi\Literal;
use Dibi\Result;


/**
 * @template M
 * @template E of Entity
 */
abstract class MapperMySQL implements MapperInterface {

  #[\DI\Attribute\Inject]
  protected \Dibi\Connection $db;


  /**
   * Seznam properties které se mají aktualizovat o proti DB
   *
   * @param Entity<E> $Entity
   * @return string[]
   */
  public function entityToRefresh(Entity $Entity): array {
    $properties = [];

    foreach($Entity->getProperties() as $Property) {
      $isAutoIncrement = $this->hasAttribute($Property, StorageInterface::class, function(StorageInterface $Attribute) {
        return $Attribute->isAutoIncrement();
      });


      if($isAutoIncrement AND !$Property->hasDefaultValue()) {
        $Entity->{$Property->getName()} = $this->db->getInsertId();
        continue;
      }

      $isAutoRefresh = $this->hasAttribute($Property, StorageInterface::class, function(StorageInterface $Attribute) {
        return $Attribute->autoRefresh();
      });

      if($isAutoRefresh) {
        $properties[] = $Property->getName();
      }
    }

    return $properties;
  }


  /**
   * @param Entity<E> $Entity
   * @return array<int, array{0: string, mixed}>
   */
  public function entityToConditions(\App\Entity\Entity $Entity): array {
    $conditions = [];

    foreach($Entity->getProperties() as $Property) {
      if($this->hasAttribute($Property, Primary::class)) {
        $conditions[] = [$Property->getName()." = ".$this->getPropertyModifier($Property), $this->getPropertyValue($Property)];
      }
    }

    if(count($conditions) === 0) {
      throw new MapperException("No primary properties for '".$Entity::class.". Define '".Primary::class."' for entity property.");
    }

    return $conditions;
  }


  /**
   * Položky k vytvoření záznamu
   *
   * @param Entity<E> $Entity
   * @return array<string, Literal>
   */
  public function entityToInsert(Entity $Entity): array {
    $data = [];

    foreach($Entity->getProperties() as $Property) {
      $skipProperty = $this->hasAttribute($Property, StorageInterface::class, function(StorageInterface $Attribute) {
        return $Attribute->isAutoIncrement() OR $Attribute->isVirtual();
      });


      if($skipProperty) {
        continue;
      }

      $modifier = $this->getPropertyModifier($Property);

      if($modifier) {
        $data[$Property->getName()] = $this->db::literal($this->db->translate($modifier, $this->getPropertyValue($Property)));
      }
    }

    return $data;
  }



  /**
   * Položky k vytvoření záznamu
   *
   * @param Entity<E> $Entity
   * @return array<string, Literal>
   */
  public function entityToUpdate(Entity $Entity): array {
    foreach($Entity->getProperties() as $Property) {

      $isVirtual = $this->hasAttribute($Property, StorageInterface::class, function(StorageInterface $Attribute) {
        return $Attribute->isVirtual();
      });
      if($isVirtual) {
        continue;
      }

      $isUndefinedPrimary = $this->hasAttribute($Property, Primary::class, function(Primary $Attribute) use ($Property) {
        return !$Property->hasDefaultValue();
      });

      if($isUndefinedPrimary) {
        continue;
      }
      $modifier = $this->getPropertyModifier($Property);

      if($modifier) {
        $data[$Property->getName()] = $this->db::literal($this->db->translate($modifier, $this->getPropertyValue($Property)));
      }
    }

    return $data;
  }


  public function entityToDelete() {

  }


  /**
   * Zda má property atribut dle callbacku
   *
   * @template AT of AttributeInterface
   * @param Property<E> $Property
   * @param class-string<AT> $attributeClassName
   * @return bool
   **/
  private function hasAttribute(Property $Property, string $attributeClassName, \Closure $Closure = null): bool {
    return count($this->findAttributes($Property, $attributeClassName, $Closure)) > 0;
  }


  /**
   * Nalezeni atributu dle callbacku
   *
   * @template AT of AttributeInterface
   * @param Property<E> $Property
   * @param class-string<AT> $attributeClassName
   * @param \Closure|null $Closure
   * @return AttributeInterface<AT>[]
   */
  private function findAttributes(Property $Property, string $attributeClassName, \Closure $Closure = null): array {
    $result = [];

    foreach($Property->getAttributes($attributeClassName) as $Attribute) {

      if(is_null($Closure) OR $Closure($Attribute) === true) {
        $result[] = $Attribute;
      }
    }

    return $result;
  }



//  /**
//   * @template T of Entity
//   * @param Entity<T> $Entity
//   * @param ?self::MODE_* $mode
//   * @return array<string, array{0: string, 1: mixed}>
//   */
//  protected function entityTo(Entity $Entity, ?string $mode = null): array {
//    $data = [];
////
////    foreach($Customer->getProperties() as $Property) {
////      foreach($Property->getAttributes(\App\Entity\Property\Storage\PropertyStorageInterface::class) as $Attribute) {
////        vd($Attribute->getName() === \App\Entity\Property\Storage\Primary::class);
////        exit;
////      }
////    }
//
//    foreach($Entity->getProperties() as $Property) {
//      $isPrimary =
//      $modifier = $this->getPropertyModifier($Property);
//
//      if($modifier) {
//        $data[$Property->getName()] = [$modifier, $this->getPropertyValue($Property)];
//      }
//    }
//
//    return $data;
//  }


  /**
   * @param Property<E> $Property
   * @return string|null
   */
  protected function getPropertyModifier(Property $Property): ?string {
    // - vrátit modifikátory podle typu property
    $Type = $Property->getType();

    if(!($Type instanceof \ReflectionNamedType)) {
      return null;
    }

    return match($Type->getName()) {
      "int", "bool", "false", "true" => "%i",
      "float" => "%f",
      "string" => "%s",
      // TODO že to je objekt
      "DateTimeInterface" => "%dt"
    };
  }


  /**
   * @param Property<E> $Property
   * @return mixed
   */
  protected function getPropertyValue(Property $Property): mixed {
    return $Property->getValueSafe();
  }

}