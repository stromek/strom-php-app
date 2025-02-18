<?php
declare(strict_types=1);

namespace App\Entity\Property;

use App\Entity\Attribute\Mutator\MutatorInterface;
use App\Entity\Attribute\AttributeInterface;
use App\Entity\Attribute\Validator\ValidatorException;
use App\Entity\Attribute\Validator\ValidatorInterface;
use App\Entity\Attribute\Value\ValueInterface;
use App\Entity\Entity;


/**
 * Entity property, responsible for the GET/SET, validation and mutation
 *
 * @template T of Entity
 */
class Property {

  private string $name;

  private \ReflectionProperty $reflection;

  /**
   * @var Entity<T>
   */
  private Entity $entity;


  /**
   * @param Entity<T> $Entity
   * @param string $name
   */
  public function __construct(Entity $Entity, string $name) {
    $this->entity = $Entity;
    $this->name = $name;

    try {
      $this->reflection = new \ReflectionProperty($Entity, $name);
    } catch(\ReflectionException $e) {
      throw new PropertyException(sprintf("Property '$%s' does not exist in '%s'.", $name, get_class($this->entity)), PropertyException::NOT_EXISTS, $e);
    }
  }


  public function getName(): string {
    return $this->name;
  }


  public function getType(): ?\ReflectionType {
    return $this->reflection->getType();
  }


  /**
   * @throws PropertyException
   */
  public function setValue(mixed $value): void {
    $method = "set".ucfirst($this->name);
    if(method_exists($this->entity, $method)) {
      $this->entity->$method($value);
      $this->validateValue($this->getValueSafe());
      return;
    }

    $value = $this->mutateValue($value);
    $this->validateValue($value);
    $this->reflection->setValue($this->entity, $value);
  }


  /**
   * Získání hodnoty property
   *
   * @throws PropertyException
   */
  public function getValue(): mixed {
    if(!$this->reflection->isInitialized($this->entity)) {
      $this->reflection->setValue($this->entity, $this->defaultValue());
    }

    $method = "get".ucfirst($this->name);
    if(method_exists($this->entity, $method)) {
      return $this->entity->$method();
    }


    if(!$this->reflection->isInitialized($this->entity)) {
      throw new PropertyException(sprintf("Typed property '%s' must not be accessed before initialization", get_class($this->entity)."::".$this->name), PropertyException::NOT_INITIALIZED);
    }

    return $this->reflection->getValue($this->entity);
  }


  /**
   * Ignore uninitialized property. In this case the null is returned
   *
   * @throws PropertyException
   */
  public function getValueSafe(): mixed {
    try {
      return $this->getValue();
    }catch(PropertyException $e) {
      if($e->getCode() === $e::NOT_INITIALIZED) {
        return null;
      }

      throw $e;
    }
  }

  public function isValid(): bool {
    return $this->isValueValid($this->getValue());
  }


  public function isValueValid(mixed $value): bool {
    try {
      $this->validateValue($value);
      return true;
    }catch(PropertyException $e) {
      return false;
    }
  }


  public function validate(bool $strict = true): void {
    if(!$strict AND !$this->hasDefaultValue()) {
      return;
    }

    $this->validateValue($this->getValue());
  }


  public function mutate(): mixed {
    $value = $this->getValue();
    $this->setValue($this->mutateValue($value));

    return $this->getValue();
  }


  /**
   * @TODO caching of instances
   *
   * @template AT of AttributeInterface
   * @param class-string<AT> $attributeClassName
   * @return AttributeInterface<AT>[]
   * @throws PropertyException
   */
  public function getAttributes(string $attributeClassName): array {
    $attributes = [];

    foreach ($this->reflection->getAttributes($attributeClassName, \ReflectionAttribute::IS_INSTANCEOF) as $Attribute) {
      if(!class_exists($Attribute->getName())) {
        throw new PropertyException(sprintf("Attribute class '%s' does not exist. Class '%s'", $Attribute->getName(), get_class($this->entity)));
      }

      $attributes[] = $Attribute->newInstance();
    }

    return $attributes;
  }


  /**
   * Zda existuje nějaký výchozí hodnota nebo hodnota s generatorem
   *
   * @throws PropertyException
   */
  public function hasDefaultValue(bool $includeAttributeValue = true): bool {
    if($this->reflection->isInitialized($this->entity)) {
      return true;
    }

    if($this->reflection->hasDefaultValue()) {
      return true;
    }

    if($includeAttributeValue AND count($this->getAttributes(ValueInterface::class)) > 0) {
      return true;
    }

    return false;
  }


  /**
   * @throws PropertyException
   */
  private function defaultValue(): mixed {
    $valueGenerators = $this->getAttributes(ValueInterface::class);

    // Není žádný výchozí hodnota ani žádný generátor
    if(!count($valueGenerators) AND !$this->hasDefaultValue(false)) {
      throw new PropertyException(sprintf("Property '%s' has no default value nor is a generator defined.", $this->entity::class."::".$this->getName()), PropertyException::NO_DEFAULT_VALUE);
    }

    $value = $this->reflection->getDefaultValue();
    foreach($valueGenerators as $AttributeValue) {
      $value = $AttributeValue->generate($value, $this->entity);
    }

    return $value;
  }


  /**
   * @throws PropertyException
   */
  private function mutateValue(mixed $value): mixed {
    $newValue = $value;

    foreach($this->getAttributes(MutatorInterface::class) as $Mutator) {
      $newValue = $Mutator->mutate($value, $this->entity);
    }

    return $newValue;
  }


  /**
   * @throws PropertyException
   */
  private function validateValue(mixed $value): void {
    foreach($this->getAttributes(ValidatorInterface::class) as $Validator) {

      try {
        $Validator->validate($value, $this->entity);
      } catch(ValidatorException $e) {
        throw new PropertyException(sprintf("Validation failed for '%s'. %s", $this->entity::class."::$".$this->getName(), $e->getMessage()), 0, $e);
      }
    }
  }


}