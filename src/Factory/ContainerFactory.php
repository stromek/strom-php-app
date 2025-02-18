<?php
declare(strict_types=1);

namespace App\Factory;

use DI\Container;


abstract class ContainerFactory {

  /**
   * @TODO možná přesunout jinam
   */
  private const DEFINTION_FILE = CONFIG_DIR."/di.definitions.php";

  private static \DI\Container $container;


  public static function create(): Container {
    if(isset(self::$container)) {
      return self::$container;
    }

    $Builder = new \DI\ContainerBuilder();
    $Builder->useAttributes(true);
    $Builder->addDefinitions(self::getDefinitions());

    return self::$container = $Builder->build();
  }


  /**
   * @return array<string, callable>
   */
  private static function getDefinitions(): array {
    if(!file_exists(self::DEFINTION_FILE)) {
      throw new \RuntimeException("DI definitions file '".self::DEFINTION_FILE."' not found");
    }

    return require(self::DEFINTION_FILE);
  }

}