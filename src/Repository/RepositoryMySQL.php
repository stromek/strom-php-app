<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Entity;
use App\Entity\EntityInterface;
use App\Mapper\MapperMySQL;
use App\Repository\Enum\RepositorySourceEnum;
use Dibi\Result;


/**
 * @phpstan-import-type DibiData from \App\Mapper\MapperMySQL
 * @phpstan-import-type DibiCondition from \App\Mapper\MapperMySQL
 */
abstract class RepositoryMySQL implements RepositoryInterface {


  protected \Dibi\Connection $db;


  public function __construct(\Dibi\Connection $db) {
    $this->db = $db;
  }


  public function getSource(): RepositorySourceEnum {
    return RepositorySourceEnum::MYSQL;
  }

  public function checkEntity(EntityInterface $Entity): void {
    $Entity->check(false);
  }



  /**
   * @param string $tableName
   * @param DibiCondition $conditions
   * @return \Dibi\Row|null
   * @throws \Dibi\Exception
   */
  protected function findRow(string $tableName, array $conditions): ?\Dibi\Row {
    $q = "SELECT * FROM %n WHERE %and";

    return $this->db->query($q, $tableName, $conditions)->fetch();
  }


  /**
   * @param string $tableName
   * @param DibiCondition $conditions
   * @param array<int, mixed> $orderBy
   * @return array<int, \Dibi\Row|array<array-key, mixed>>
   */
  protected function findAll(string $tableName, array $conditions, array $orderBy = []): array {
    $orderBySql = [];
    foreach($orderBy as $column) {
      $orderBySql[] = $this->db->translate($column);
    }

    return $this->db->query(
      "SELECT * FROM %n WHERE %and %SQL",
      $tableName,
      $conditions,
      (count($orderBySql) ? "ORDER BY " . implode(", ", $orderBySql) : ""))
    ->fetchAll();
  }


  /**
   * @throws \Dibi\Exception|\App\Mapper\MapperException|RepositoryException
   */
  protected function insertEntity(MapperMySQL $Mapper, EntityInterface $Entity, string $tableName): Result {
    $this->checkEntity($Entity);
    $Result = $this->insertRow($tableName, $Mapper->entityToInsert($Entity));

    $refreshProperties = $Mapper->entityToRefresh($Entity);
    if(count($refreshProperties)) {
      $this->refreshEntity($Entity, $tableName, $refreshProperties, $Mapper->entityToConditions($Entity));
    }

    return $Result;
  }


  /**
   * @param string $tableName
   * @param DibiData $data
   * @throws \Dibi\Exception
   */
  protected function insertRow(string $tableName, array $data): Result {
    $q = "INSERT INTO %n SET %a";
    return $this->db->query($q, $tableName, $data);
  }


  /**
   * @param string $tableName
   * @throws \Dibi\Exception|\App\Mapper\MapperException|RepositoryException
   */
  protected function updateEntity(MapperMySQL $Mapper, EntityInterface $Entity, string $tableName): Result {
    $this->checkEntity($Entity);
    $Result = $this->updateRow($tableName, $Mapper->entityToUpdate($Entity), $Mapper->entityToConditions($Entity));

    $refreshProperties = $Mapper->entityToRefresh($Entity);
    if(count($refreshProperties)) {
      $this->refreshEntity($Entity, $tableName, $refreshProperties, $Mapper->entityToConditions($Entity));
    }

    return $Result;
  }


  /**
   * @param string $tableName
   * @param DibiData $data
   * @param DibiCondition $conditions
   * @throws \Dibi\Exception
   */
  protected function updateRow(string $tableName, array $data, array $conditions): Result {
    $q = "UPDATE %n SET %a WHERE %and";
    return $this->db->query($q, $tableName, $data, $conditions);
  }


  /**
   * @throws \Dibi\Exception|\App\Mapper\MapperException
   */
  protected function deleteEntity(MapperMySQL $Mapper, EntityInterface $Entity, string $tableName): Result {
    $this->checkEntity($Entity);
    return $this->deleteRow($tableName, $Mapper->entityToConditions($Entity));
  }


  /**
   * @param string $tableName
   * @param DibiCondition $conditions
   * @param int $limit
   * @return Result
   * @throws \Dibi\Exception
   */
  protected function deleteRow(string $tableName, array $conditions, int $limit = 1): Result {
    $q = "DELETE FROM %n WHERE %and LIMIT %i";
    return $this->db->query($q, $tableName, $conditions, $limit);
  }


  /**
   * Refresh hodnot z databáze do entity
   *
   * @param string[] $propertiesNames názvy property/názvy sloupců
   * @param DibiCondition $conditions
   * @return void
   * @throws \Dibi\Exception|RepositoryException
   */
  protected function refreshEntity(EntityInterface $Entity, string $tableName, array $propertiesNames, array $conditions): void {
    if(!count($propertiesNames)) {
      throw new RepositoryException("At least one property must be defined in \$properties");
    }
    if(!count($conditions)) {
      throw new RepositoryException("At least one condition must be defined in \$conditions");
    }

    $q = "
      SELECT %n
      FROM %n
      WHERE %and
      LIMIT 1
    ";
    $Result = $this->db->query($q, $propertiesNames, $tableName, $conditions)->fetch();

    foreach($propertiesNames as $property) {
      $Entity->{$property} = $Result->{$property};
    }
  }

  
}