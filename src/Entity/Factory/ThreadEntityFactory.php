<?php
declare(strict_types=1);

namespace App\Entity\Factory;


use App\Entity\ThreadEntity;


class ThreadEntityFactory extends EntityFactory {

  /**
   * @param array<string, mixed> $attributes
   */
  public function createThread(array $attributes = []): ThreadEntity {
    return $this->createEntity(ThreadEntity::class, $attributes);
  }

}