<?php
declare(strict_types=1);

namespace App\Mapper\Customer;

use App\Entity\Factory\CustomerEntityFactory;


/**
 * @extends \App\Mapper\MapperMySQL<\App\Entity\Entity>
 */
class CustomerMapperMySQL extends \App\Mapper\MapperMySQL {

  private CustomerEntityFactory $factory;

  
  public function __construct(CustomerEntityFactory $Factory) {
    $this->factory = $Factory;
  }


  public function createCustomerEntity(\Dibi\Row $Row): \App\Entity\CustomerEntity {
    return $this->factory->createCustomer($Row->toArray());
  }

}