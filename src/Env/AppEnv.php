<?php
declare(strict_types=1);

namespace App\Env;


use Dotenv\Dotenv;
use Tracy\Debugger;
use RuntimeException;


abstract class AppEnv {

  private static Dotenv $dotEnv;

  /**
   * @var array<string, string|null>
   */
  private static array $environmentValues;


  /**
   * @return string|array<string, string|null>
   */
  public static function get(?string $key = null): string|array {
    self::lazyLoad();

    if(is_null($key)) {
      return self::$environmentValues;
    }

    if(!isset(self::$environmentValues[$key])) {
      throw new RuntimeException("Key '{$key}' does not exist");
    }

    return self::$environmentValues[$key];
  }


  /**
   * @param string[] $keys
   */
  public static function requiredEnvironment(array $keys): void {
    self::getDotEnv()->required($keys);
  }


  public static function displayInternalError(): bool {
    return !self::isProduction() OR self::isDeveloper();
  }


  /**
   * @TODO to implement
 */
  public static function isProduction(): bool {
    return Debugger::$productionMode ?? true;
  }


  /**
   * Is current user is developer (show errors)
   * @TODO to implement
   */
  public static function isDeveloper(): bool {
    return !Debugger::$productionMode;
  }


  private static function getDotEnv(): Dotenv {
    self::lazyLoad();

    return self::$dotEnv;
  }


  private static function lazyLoad(): void {
    if(isset(self::$dotEnv)) {
      return;
    }

    self::$dotEnv = Dotenv::createImmutable(ROOT_DIR);
    self::$environmentValues = self::$dotEnv->load();
  }
}