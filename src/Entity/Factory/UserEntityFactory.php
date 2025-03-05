<?php
declare(strict_types=1);

namespace App\Entity\Factory;


use App\Entity\UserEntity;


class UserEntityFactory extends EntityFactory {

  /**
   * @param array<string, mixed> $attributes
   */
  public function createUser(array $attributes = []): UserEntity {
    return $this->createEntity(UserEntity::class, $attributes);
  }

}