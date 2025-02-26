<?php
declare(strict_types=1);

use App\Env\AppEnv;
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
  }
];