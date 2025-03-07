<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\ThreadEntity;
use App\Mapper\ThreadMapperMySQL;
use Dibi\Connection;


/**
 * @phpstan-import-type DibiCondition from \App\Mapper\MapperMySQL
 * @template E of ThreadEntity
 * @extends RepositoryMySQL<E>
 */
class ThreadRepositoryMySQL extends RepositoryMySQL {

  /**
   * @var ThreadMapperMySQL<E>
   */
  private ThreadMapperMySQL $mapper;


  /**
   * @param Connection $db
   * @param ThreadMapperMySQL<E> $Mapper
   */
  public function __construct(Connection $db, ThreadMapperMySQL $Mapper) {
    parent::__construct($db);

    $this->mapper = $Mapper;
  }


  public function findByCustomerIDAndHash(int $customer_id, string $hash): ThreadEntity {
    return $this->findByCondition([["customer_id = %i", $customer_id], ["hash = %s", $hash]]);
  }


  public function findByCustomerIDAndCode(int $customer_id, string $code): ThreadEntity {
    return $this->findByCondition([["customer_id = %i", $customer_id], ["code = %s", $code]]);
  }


  /**
   * @throws RepositoryException
   * @throws \Dibi\Exception
   */
  public function getIDByCustomerIDAndHash(int $customer_id, string $hash): int {
    $thread_id = $this->db->query("SELECT id FROM thread WHERE customer_id = %i AND hash = %s", $customer_id, $hash)->fetchSingle();

    if(!$thread_id) {
      throw new RepositoryException("Thread hash #{$hash} not found.", RepositoryException::NOT_FOUND);
    }

    return $thread_id;
  }


  public function findByCustomerAndID(int $customer_id, int $id): ThreadEntity {
    return  $this->findByCondition([["customer_id = %i", $customer_id], ["id = %i", $id]]);
  }


  public function findByID(int $id): ThreadEntity {
    return $this->findByCondition([["id = %i", $id]]);
  }


  /**
   * @param DibiCondition $conditions
   * @throws RepositoryException
   * @throws \Dibi\Exception
   */
  private function findByCondition(array $conditions): ThreadEntity {
    $Row = $this->findRow("thread", $conditions);

    if(!$Row) {
      throw new RepositoryException("Thread not found.", RepositoryException::NOT_FOUND);
    }

    return $this->mapper->createThreadEntity($Row);
  }


}