<?php
declare(strict_types=1);

namespace App\Entity\Factory;


use App\Entity\MessageEntity;


class MessageEntityFactory extends EntityFactory {

  /**
   * @param array<string, mixed> $attributes
   */
  public function createMessage(array $attributes = []): MessageEntity {
    return $this->createEntity(MessageEntity::class, $attributes);
  }

}