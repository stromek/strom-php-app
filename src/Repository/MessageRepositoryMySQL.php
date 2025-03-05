<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\MessageEntity;
use App\Mapper\MessageMapperMySQL;
use Dibi\Connection;


/**
 * @template E of MessageEntity
 * @extends RepositoryMySQL<E>
 */
class MessageRepositoryMySQL extends RepositoryMySQL {

  /**
   * @var MessageMapperMySQL<E>
   */
  private MessageMapperMySQL $mapper;


  /**
   * @param Connection $db
   * @param MessageMapperMySQL<E> $Mapper
   */
  public function __construct(Connection $db, MessageMapperMySQL $Mapper) {
    parent::__construct($db);

    $this->mapper = $Mapper;
  }


  public function findByID(int $id): MessageEntity {
    $Row = $this->findRow("message", [["id = %i", $id]]);

    if(!$Row) {
      throw new RepositoryException("Message ID #{$id} not found.", RepositoryException::NOT_FOUND);
    }

    return $this->mapper->createMessageEntity($Row);
  }


  /**
   * @return array<int, MessageEntity>
   */
  public function findAllByCustomerIDAndThreadID(int $customer_id, int $thread_id): array {
    return \App\Util\Arr::create(
      $this->findAll(
        "message",
        [["customer_id = %i", $customer_id], ["thread_id = %i", $thread_id]],
        [["createdAt ASC"], ["id ASC"]]
      ))
      ->map(fn(\Dibi\Row $Row) => $this->mapper->createMessageEntity($Row))
      ->toArray();
  }

}