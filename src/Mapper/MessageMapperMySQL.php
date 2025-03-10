<?php
declare(strict_types=1);

namespace App\Mapper;

use App\Entity\Factory\MessageEntityFactory;


class MessageMapperMySQL extends MapperMySQL {

  private MessageEntityFactory $factory;

  
  public function __construct(MessageEntityFactory $Factory) {
    $this->factory = $Factory;
  }

  public function createMessageEntity(\Dibi\Row $Row): \App\Entity\MessageEntity {
    return $this->factory->createMessage($Row->toArray());
  }

}