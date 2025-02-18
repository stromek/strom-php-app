<?php
declare(strict_types=1);

namespace App\Entity\Factory;



use App\Entity\Entity;


abstract class EntityFactory {


  /**
   * @template T of Entity
   * @param class-string<T> $entityClassName
   * @param array<string, mixed> $attributes
   * @param mixed ...$args
   * @return T
   */
  protected function createEntity(string $entityClassName, array $attributes = [], ...$args): Entity {
    $Entity = new $entityClassName(...$args);
    $this->setAttributes($Entity, $attributes);

    return $Entity;
  }

  /**
   * @template T of Entity
   * @param Entity<T> $Entity
   * @param array<array-key, mixed> $attributes
   */
  protected function setAttributes(Entity $Entity, array $attributes = []): void {
    foreach($attributes as $name => $value) {
      $Entity->{$name} = $value;
    }
  }

}