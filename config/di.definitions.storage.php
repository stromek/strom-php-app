<?php
declare(strict_types=1);

use App\Env\AppEnv;
use Aws\DynamoDb\DynamoDbClient;
use Elastic\Elasticsearch\ClientBuilder;
use Psr\Container\ContainerInterface;


return [
  \Dibi\Connection::class => function (ContainerInterface $Container): \Dibi\Connection {
    AppEnv::requiredEnvironment(["DB_MYSQL_HOST", "DB_MYSQL_USER", "DB_MYSQL_PASSWORD", "DB_MYSQL_DATABASE"]);

    $options = [
      'host' => AppEnv::get("DB_MYSQL_HOST"),
      'username' => AppEnv::get("DB_MYSQL_USER"),
      'password' => AppEnv::get("DB_MYSQL_PASSWORD"),
      'database' => AppEnv::get("DB_MYSQL_DATABASE"),
      "lazy" => true
    ];

    return new \Dibi\Connection($options);
  },


  DynamoDbClient::class => function (ContainerInterface $Container): DynamoDbClient {
    AppEnv::requiredEnvironment(["DB_DYNAMO_REGION", "DB_DYNAMO_VERSION", "DB_DYNAMO_ENDPOINT", "DB_DYNAMO_ACCESS_KEY_ID", "DB_DYNAMO_SECRET_ACCESS_KEY"]);

    $options = [
      'region'=> AppEnv::get("DB_DYNAMO_REGION"),
      'version'=> AppEnv::get("DB_DYNAMO_VERSION"),
      'endpoint'=> AppEnv::get("DB_DYNAMO_ENDPOINT"),
      'credentials' => [
        'key' => AppEnv::get("DB_DYNAMO_ACCESS_KEY_ID"),
        'secret' => AppEnv::get("DB_DYNAMO_SECRET_ACCESS_KEY")
      ]
    ];

    return new DynamoDbClient($options);
  }
];