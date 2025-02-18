<?php
declare(strict_types=1);

namespace App\Entity\Property;


use App\Entity\Attribute\Storage\StorageException;
use App\Entity\Entity;


/**
 * @template T of Entity
 */
class PropertyStorage {

  /**
   * @var Property<T>[]
   */
  private array $properties;

  /**
   * @var Entity<T>
   */
  private Entity $entity;


  /**
   * @param Entity<T> $Entity
   */
  public function __construct(Entity $Entity) {
    $this->entity = $Entity;
  }


  public function set(string $name, mixed $value): void {
    $this->getProperty($name)->setValue($value);
  }


  public function get(string $name): mixed {
    return $this->getProperty($name)->getValue();
  }


  public function mutate(): void {
    foreach($this->getProperties() as $Property) {
      $Property->mutate();
    }
  }


  public function validate(bool $strict = true): void {
    foreach($this->getProperties() as $Property) {
      $Property->validate($strict);
    }
  }


  /**
   * @return Property<T>[]
   * @throws StorageException
   * @throws PropertyException
   * @throws \ReflectionException
   */
  public function getProperties(): array {
    if(isset($this->properties)) {
      return $this->properties;
    }
    $this->properties = [];


    $ReflectionClass = new \ReflectionClass($this->entity);
    $pattern = '~@property\s+(?P<type>[^\s]+)\s+\$(?P<name>[^\s]+)~';

    if(!preg_match_all($pattern, $ReflectionClass->getDocComment() ?: "", $matches, PREG_SET_ORDER)) {
      throw new StorageException("No '@property' string found in docs class of '".get_class($this->entity)."'");
    }

    foreach ($matches as $match) {
      $Property = new Property($this->entity, $match['name']);
      $this->properties[$Property->getName()] = $Property;
    }
    

    return $this->properties;
  }


  /**
   * @param string $name
   * @return Property<T>
   * @throws StorageException
   * @throws PropertyException
   * @throws \ReflectionException
   */
  public function getProperty(string $name): Property {
    $Property = $this->getProperties()[$name] ?? null;

    if(!$Property) {
      throw new PropertyStorageException("Property '{$name}' does not exist in ".get_class($this->entity));
    }

    return $Property;
  }


//  protected function setPropertyValue(string $name, mixed $value): mixed {
//    $Property = $this->getProperty($name);
//    $Property->setValue($value);
//
//    return $Property->getValue();
//  }
}
