<?php
declare(strict_types=1);

use App\Http\Session;
use Aws\DynamoDb\DynamoDbClient;
use Psr\Container\ContainerInterface;


return [
  \GuzzleHttp\Psr7\Request::class => function () {
    $headers = [];
    foreach ($_SERVER as $key => $value) {
      if(str_starts_with($key, 'HTTP_')) {
        $headerName = str_replace('_', '-', substr($key, 5));
        $headers[$headerName] = $value;
      }
    }

    $method = null;
    $uri = null;

    switch(php_sapi_name()) {
      case "cli-server";
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
      break;
        
      default;
        $method = filter_input(INPUT_SERVER, "REQUEST_METHOD");
        $uri = filter_input(INPUT_SERVER, "REQUEST_URI");
    };

    return new \GuzzleHttp\Psr7\Request(
      $method,
      $uri,
      $headers,
      file_get_contents('php://input') ?: ""
    );
  },


  Session::class => function (ContainerInterface $Container): Session {
    $SessionHandler = \Aws\DynamoDb\SessionHandler::fromClient($Container->get(DynamoDbClient::class), [
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

    $SessionHandler->register();

    return new Session($Container->get(\App\Api\Request\Request::class));
  },

  \App\Api\Request\RequestInterface::class => DI\autowire(\App\Api\Request\Request::class),
  \App\Api\Response\ResponseInterface::class => DI\autowire(\App\Api\Response\Response::class),
];