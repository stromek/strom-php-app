<?php
declare(strict_types=1);

namespace App\Mapper\Customer;

use App\Entity\Factory\CustomerEntityFactory;
use App\Mapper\MapperMySQL;


/**
 * @template E of \App\Entity\Entity
 * @extends MapperMySQL<E>
 */
class CustomerMapperMySQL extends MapperMySQL {

  private CustomerEntityFactory $factory;

  
  public function __construct(CustomerEntityFactory $Factory) {
    $this->factory = $Factory;
  }


  public function createCustomerEntity(\Dibi\Row $Row): \App\Entity\CustomerEntity {
    return $this->factory->createCustomer($Row->toArray());
  }

}