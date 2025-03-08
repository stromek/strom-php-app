<?php
declare(strict_types=1);

namespace App\Entity\Property;


use App\Entity\Attribute\Storage\StorageException;
use App\Entity\EntityInterface;


class PropertyStorage {

  /**
   * @var array<string, Property>
   */
  private array $properties;


  private EntityInterface $entity;


  public function __construct(EntityInterface $Entity) {
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
   * @return array<string, Property>
   * @throws PropertyException
   * @throws StorageException
   */
  public function getProperties(): array {
    if(isset($this->properties)) {
      return $this->properties;
    }
    $this->properties = [];


    $ReflectionClass = new \ReflectionClass($this->entity);

    $pattern = '~@property\s+(?P<type>[^\s]+)\s+\$(?P<name>[^\s]+)~';
    if(!preg_match_all($pattern, $ReflectionClass->getDocComment() ?: "", $matches, PREG_SET_ORDER)) {
      throw new StorageException("No '@property' string found in docs class of '".$this->entity::class."'");
    }

    foreach ($matches as $match) {
      $Property = new Property($this->entity, $match['name']);
      $this->properties[$Property->getName()] = $Property;
    }


    return $this->properties;
  }


  /**
   * @param string $name
   * @return Property
   * @throws PropertyException
   * @throws PropertyStorageException
   * @throws StorageException
   */
  public function getProperty(string $name): Property {
    $Property = $this->getProperties()[$name] ?? null;

    if(!$Property) {
      throw new PropertyStorageException("Property '{$name}' does not exist in '".$this->entity::class."'");
    }

    return $Property;
  }

}
