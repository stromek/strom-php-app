<?php
declare(strict_types=1);

namespace App\Factory;

use App\Exception\AppException;
use DI\Container;


/**
 * @phpstan-type mode self::MODE_*
 */
abstract class ContainerFactory {

  const MODE_DEFAULT = "default";

  const MODE_PHPUNIT = "phpunit";

  /**
   * @var array<mode, array<string>>
   */
  private static array $definitions = [
    self::MODE_DEFAULT => [
      CONFIG_DIR."/di.definitions.php"
    ],
    self::MODE_PHPUNIT => [
      CONFIG_DIR."/di.definitions.php",
      CONFIG_DIR."/phpunit/di.phpunit.definitions.php"
    ],
  ];


  /**
   * @var array<mode, \DI\Container>
   */
  private static array $container = [];

  /**
   * @param self::MODE_* $mode
   * @return Container
   * @throws \Exception
   */
  public static function create(string $mode = self::MODE_DEFAULT): Container {
    if(isset(self::$container[$mode])) {
      return self::$container[$mode];
    }

    $Builder = new \DI\ContainerBuilder();
    $Builder->useAttributes(true);
    $Builder->addDefinitions(self::getDefinitions($mode));

    return self::$container[$mode] = $Builder->build();
  }


  /**
   * @return array<string, callable>
   */
  private static function getDefinitions(string $mode): array {
    $files = self::$definitions[$mode] ?? null;
    if(!$files) {
      throw new AppException("Unknown mode '{$mode}', cannot create definitions");
    }

    $definitions = [];
    foreach($files as $file) {
      if(!file_exists($file)) {
        throw new AppException("DI definitions file '{$file}' not found");
      }

      $definitions = array_merge($definitions, require($file));
    }


    return $definitions;
  }

}