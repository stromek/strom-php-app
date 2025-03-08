<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\CustomerAuthEntity;
use App\Mapper\CustomerAuthMapperMySQL;
use Dibi\Connection;


/**
 * @phpstan-import-type DibiCondition from \App\Mapper\MapperMySQL
 */
class CustomerAuthRepositoryMySQL extends RepositoryMySQL {

  private CustomerAuthMapperMySQL $mapper;


  public function __construct(Connection $db, CustomerAuthMapperMySQL $Mapper) {
    parent::__construct($db);

    $this->mapper = $Mapper;
  }


  public function findAuth(\App\Entity\Enum\CustomerAuthTypeEnum $authType, string $authValue): CustomerAuthEntity {
    $Row = $this->findRow("customerAuth", [
      ["authType = %s", $authType->value],
      ["authValue = %s", $authValue],
    ]);

    if(!$Row) {
      throw new RepositoryException("Customer auth failedfound.", RepositoryException::NOT_FOUND);
    }

    return $this->mapper->createCustomerAuthEntity($Row);
  }


}