<?php
declare(strict_types=1);

namespace App\Repository\Customer;

use App\Entity\CustomerEntity;
use App\Mapper\Customer\CustomerMapperMySQL;
use App\Repository\RepositoryException;
use App\Repository\RepositoryMySQL;
use Dibi\Connection;


/**
 * @extends RepositoryMySQL<\App\Entity\Entity, CustomerMapperMySQL>
 */
class CustomerRepositoryMySQL extends RepositoryMySQL {

  private CustomerMapperMySQL $mapper;

  public function __construct(Connection $db, CustomerMapperMySQL $Mapper) {
    parent::__construct($db);

    $this->mapper = $Mapper;
  }


  public function findByID(int $id): CustomerEntity {
    $Row = $this->findRow("customer", [["id = %i", $id]]);

    if(!$Row) {
      throw new RepositoryException("Customer ID #{$id} not found.", RepositoryException::NOT_FOUND);
    }

    return $this->mapper->createCustomerEntity($Row);
  }

  public function insertCustomer(CustomerEntity $Customer): void {
    if($this->insertEntity($this->mapper, $Customer, "customer")->getRowCount() !== 1) {
      throw new RepositoryException("Create customer failed.", RepositoryException::INSERT_FAILED);
    }
  }

  public function updateCustomer(CustomerEntity $Customer): void {
    if($this->updateEntity($this->mapper, $Customer, "customer")->getRowCount() === 0) {
      throw new RepositoryException("Update customer #{$Customer?->id} failed.", RepositoryException::UPDATE_FAILED);
    }
  }

  public function deleteCustomer(CustomerEntity $Customer): void {
    if($this->deleteEntity($this->mapper, $Customer, "customer")->getRowCount() !== 1) {
      throw new RepositoryException("Delete customer #{$Customer?->id} failed.", RepositoryException::DELETE_FAILED);
    }
  }

}