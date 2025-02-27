<?php
declare(strict_types=1);
require __DIR__.'/../../vendor/autoload.php';
require __DIR__.'/../../boot/bootstrap.php';

use Aws\DynamoDb\DynamoDbClient;

$Container = \App\Factory\ContainerFactory::create();

$Client = $Container->get(DynamoDbClient::class);

foreach(glob("*.table.json") as $file) {
  $filename = __DIR__.DIRECTORY_SEPARATOR.$file;

  $definition = json_decode(file_get_contents($filename) ?: "null", true);

  if(!$definition) {
    trigger_error(sprintf("Cannot parse file '%s' as JSON file.", $filename), E_USER_ERROR);
  }

  try {
    $Client->createTable($definition);
    echo sprintf("Table '%s' created successfully.\n", $definition['TableName']);
  } catch (\Aws\Exception\AwsException $e) {
    echo "Error creating table: " . $e->getMessage() . "\n";
  }
}

