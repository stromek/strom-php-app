<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\CustomerEntity;
use App\Mapper\CustomerMapperMySQL;
use Dibi\Connection;


/**
 * @phpstan-import-type DibiCondition from \App\Mapper\MapperMySQL
 * @template E of CustomerEntity
 * @extends RepositoryMySQL<E>
 */
class CustomerRepositoryMySQL extends RepositoryMySQL {

  /**
   * @var CustomerMapperMySQL<E>
   */
  private \App\Mapper\CustomerMapperMySQL $mapper;


  /**
   * @param Connection $db
   * @param CustomerMapperMySQL<E> $Mapper
   */
  public function __construct(Connection $db, CustomerMapperMySQL $Mapper) {
    parent::__construct($db);

    $this->mapper = $Mapper;
  }
  

  public function findByClientKey(string $clientKey): CustomerEntity {
    return $this->findByCondition([["clientKey = %s", $clientKey]]);
  }


  public function findByAuthToken(string $authToken): CustomerEntity {
    return $this->findByCondition([["authToken = %s", $authToken], ["authToken != ''"]]);
  }


  public function findByID(int $id): CustomerEntity {
    return $this->findByCondition([["id = %i", $id]]);
  }


  /**
   * @param DibiCondition $conditions
   * @throws RepositoryException
   * @throws \Dibi\Exception
   */
  private function findByCondition(array $conditions): CustomerEntity {
    $Row = $this->findRow("customer", $conditions);

    if(!$Row) {
      throw new RepositoryException("Customer not found.", RepositoryException::NOT_FOUND);
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
      throw new RepositoryException("Update customer #{$Customer->id} failed.", RepositoryException::UPDATE_FAILED);
    }
  }

  public function deleteCustomer(CustomerEntity $Customer): void {
    if($this->deleteEntity($this->mapper, $Customer, "customer")->getRowCount() !== 1) {
      throw new RepositoryException("Delete customer #{$Customer->id} failed.", RepositoryException::DELETE_FAILED);
    }
  }

}