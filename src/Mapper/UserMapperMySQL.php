<?php
declare(strict_types=1);

namespace App\Mapper;

use App\Entity\Factory\UserEntityFactory;


/**
 * @template E of \App\Entity\Entity
 * @extends MapperMySQL<E>
 */
class UserMapperMySQL extends MapperMySQL {

  private UserEntityFactory $factory;

  
  public function __construct(UserEntityFactory $Factory) {
    $this->factory = $Factory;
  }

  public function createUserEntity(\Dibi\Row $Row): \App\Entity\UserEntity {
    return $this->factory->createUser($Row->toArray());
  }

}