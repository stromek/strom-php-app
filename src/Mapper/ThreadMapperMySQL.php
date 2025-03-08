<?php
declare(strict_types=1);

namespace App\Mapper;

use App\Entity\Factory\ThreadEntityFactory;


class ThreadMapperMySQL extends MapperMySQL {

  private ThreadEntityFactory $factory;

  
  public function __construct(ThreadEntityFactory $Factory) {
    $this->factory = $Factory;
  }

  public function createThreadEntity(\Dibi\Row $Row): \App\Entity\ThreadEntity {
    return $this->factory->createThread($Row->toArray());
  }

}