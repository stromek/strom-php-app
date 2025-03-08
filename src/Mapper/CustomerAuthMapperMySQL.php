<?php
declare(strict_types=1);

namespace App\Mapper;

use App\Entity\Factory\CustomerAuthEntityFactory;


class CustomerAuthMapperMySQL extends MapperMySQL {

  private CustomerAuthEntityFactory $factory;

  
  public function __construct(CustomerAuthEntityFactory $Factory) {
    $this->factory = $Factory;
  }


  public function createCustomerAuthEntity(\Dibi\Row $Row): \App\Entity\CustomerAuthEntity {
    return $this->factory->createCustomerAuth($Row->toArray());
  }

}