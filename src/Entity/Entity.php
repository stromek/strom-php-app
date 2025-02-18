<?php
declare(strict_types=1);

namespace App\Entity;

use App\Entity\Attribute\PropertyException;
use App\Entity\Property\Property;
use App\Entity\Property\PropertyStorage;
use App\Exception\EntityException;


/**
 * @template T of Entity
 */
abstract class Entity implements \JsonSerializable, \App\Xml\XMLSerializable {

  /**
   * @var PropertyStorage<T>
   */
  readonly protected PropertyStorage $attributeStorage;

  public function __construct() {
    $this->attributeStorage = new PropertyStorage($this);
  }

  /**
   * @throws PropertyException
   * @throws EntityException
   */
  public function __set(string $name, mixed $value): void {
    $this->getProperty($name)->setValue($value);
  }

  /**
   * @throws EntityException
   */
  public function __get(string $name): mixed {
    return $this->getProperty($name)->getValue();
  }

  public function check(bool $strict = true): void {
//    $this->mutate();
    $this->validate($strict);
  }

  public function mutate(): void {
    $this->attributeStorage->mutate();
  }

  public function validate(bool $strict = true): void {
    $this->attributeStorage->validate($strict);
  }

  /**
   * @return Property<T>[]
   */
  public function getProperties(): array {
    return $this->attributeStorage->getProperties();
  }


  /**
   * @return \App\Entity\Property\Property<T>
   */
  public function getProperty(string $name): Property {
    return $this->attributeStorage->getProperty($name);
  }


  /**
   * @return array<array-key, mixed>
   */
  public function toArray(): array {
    return array_map(function(Property $Property): mixed {
      return $Property->getValueSafe();
    }, $this->getProperties());
  }

  /**
   * @return array<array-key, mixed>
   */
  public function xmlSerialize(): array {
    return $this->jsonSerialize();
  }


  /**
   * @return array<array-key, mixed>
   */
  public function jsonSerialize(): array {
    return array_map(function(mixed $value): mixed {
      if($value instanceof \BackedEnum) {
        return $value->value;
      }

      if($value instanceof \UnitEnum) {
        return $value->name;
      }

      return $value;
    }, $this->toArray());
  }
}
