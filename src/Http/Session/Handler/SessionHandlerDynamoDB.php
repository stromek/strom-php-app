<?php
declare(strict_types=1);

namespace App\Http\Session\Handler;

class SessionHandlerDynamoDB implements SessionHandlerInterface {

  private \Aws\DynamoDb\SessionHandler $handler;

  public function __construct(\DI\Container $Container) {
    $this->handler = \Aws\DynamoDb\SessionHandler::fromClient($Container->get(\Aws\DynamoDb\DynamoDbClient::class), [
      'table_name' => 'session',
      'locking_strategy' => 'optimisti',

      // Primarni klic pro ulozeni session_id
      'hash_key' => 'id',

      // Atribut kde budou data a jakeho typu (string, může být i binary)
      'data_attribute' => 'data',
      'data_attribute_type' => 'string',

      'session_lifetime' => 3600,
      'session_lifetime_attribute' => 'expires',

      'consistent_read' => true,
      'locking' => false,

      //  'batch_config' => [],
      // Maximum time (in seconds) that the session handler should wait to acquire a lock before giving up. The default to is 10 and is only used with session locking.
      'max_lock_wait_time' => 10,
      // Minimum time (in microseconds) that the session handler should wait between attempts to acquire a lock. The default is 10000 and is only used with session locking.
      'min_lock_retry_microtime' => 5000,
      // Maximum time (in microseconds) that the session handler should wait between attempts to acquire a lock. The default is 50000 and is only used with session locking.
      'max_lock_retry_microtime' => 50000,
    ]);

  }

  public function register(): bool {
    return $this->handler->register();
  }

}