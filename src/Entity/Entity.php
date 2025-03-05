<?php
declare(strict_types=1);

namespace App\Entity;

use OpenApi\Attributes as OA;
use App\Entity\Attribute\PropertyException;
use App\Entity\Property\Property;
use App\Entity\Property\PropertyStorage;


/**
 * @template E of Entity
 */
abstract class Entity implements \JsonSerializable, \App\Xml\XMLSerializable {

  /**
   * @var PropertyStorage<E>
   */
  readonly protected PropertyStorage $attributeStorage;

  public function __construct() {
    $this->attributeStorage = new PropertyStorage($this);
  }

  public function __set(string $name, mixed $value): void {
    $this->getProperty($name)->setValue($value);
  }

  public function __get(string $name): mixed {
    return $this->getProperty($name)->getValue();
  }

  public function check(bool $strict = true): void {
    // @TODO mutate spustit ci ne?
    // $this->mutate();
    $this->validate($strict);
  }

  public function mutate(): void {
    $this->attributeStorage->mutate();
  }

  public function validate(bool $strict = true): void {
    $this->attributeStorage->validate($strict);
  }

  /**
   * @return Property<E>[]
   */
  public function getProperties(): array {
    return $this->attributeStorage->getProperties();
  }


  /**
   * @return Property<E>
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
