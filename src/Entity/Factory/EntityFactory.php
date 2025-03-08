<?php
declare(strict_types=1);

namespace App\Entity\Factory;



use App\Entity\Entity;
use App\Entity\EntityInterface;


abstract class EntityFactory {


  /**
   * @template T of EntityInterface
   * @param class-string<T> $entityClassName
   * @param array<string, mixed> $attributes
   * @param mixed ...$args
   * @return T
   */
  protected function createEntity(string $entityClassName, array $attributes = [], ...$args): EntityInterface {
    $Entity = new $entityClassName(...$args);
    $this->setAttributes($Entity, $attributes);

    return $Entity;
  }

  /**
   * @param array<array-key, mixed> $attributes
   */
  protected function setAttributes(EntityInterface $Entity, array $attributes = []): void {
    foreach($attributes as $name => $value) {
      $Entity->{$name} = $value;
    }
  }

}