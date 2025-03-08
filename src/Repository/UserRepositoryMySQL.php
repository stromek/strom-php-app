<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\UserEntity;
use App\Mapper\UserMapperMySQL;
use Dibi\Connection;


/**
 * @phpstan-import-type DibiCondition from \App\Mapper\MapperMySQL
 */
class UserRepositoryMySQL extends RepositoryMySQL {

  private UserMapperMySQL $mapper;
  

  public function __construct(Connection $db, UserMapperMySQL $Mapper) {
    parent::__construct($db);

    $this->mapper = $Mapper;
  }


  public function findByID(int $id): UserEntity {
    return $this->findByCondition([["id = %i", $id]]);
  }


  public function findByCustomerIDAndHash(int $customer_id, string $hash): UserEntity {
    return $this->findByCondition([["customer_id = %i", $customer_id], ["hash = %s", $hash]]);
  }


  /**
   * @param DibiCondition $conditions
   * @throws RepositoryException
   * @throws \Dibi\Exception
   */
  private function findByCondition(array $conditions): UserEntity {
    $Row = $this->findRow("user", $conditions);

    if(!$Row) {
      throw new RepositoryException("User not found.", RepositoryException::NOT_FOUND);
    }

    return $this->mapper->createUserEntity($Row);
  }


  /**
   * @param int $customer_id
   * @return array<int, UserEntity>
   */
  public function findAllByCustomerID(int $customer_id): array {
    return \App\Util\Arr::create($this->findAll("user", [["customer_id = %i", $customer_id]]))
      ->map(fn(\Dibi\Row $Row) => $this->mapper->createUserEntity($Row))
      ->toArray();
  }


  /*public function insertCustomer(CustomerEntity $Customer): void {
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
  }*/

}