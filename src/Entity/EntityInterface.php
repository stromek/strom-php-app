<?php
declare(strict_types=1);

namespace App\Entity;

use App\Entity\Property\Property;
use App\Entity\Attribute\PropertyException;


interface EntityInterface extends \JsonSerializable, \App\Xml\XMLSerializable {


  public function __set(string $name, mixed $value): void;

  public function __get(string $name): mixed;

  public function check(bool $strict = true): void;

  public function mutate(): void;


  public function validate(bool $strict = true): void;

  /**
   * @return array<string, Property>
   **/
  public function getProperties(): array;

  public function getProperty(string $name): Property;

  /**
   * @return array<string, mixed>
   */
  public function toArray(): array;
}
