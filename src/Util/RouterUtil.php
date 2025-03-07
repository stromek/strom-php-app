<?php
declare(strict_types=1);
namespace App\Util;

use App\Env\AppEnv;
use Tracy\Debugger;


abstract class RouterUtil {


  /**
   * Detaily k vyjímce
   *
   * @param \Throwable $Exception
   * @return array<array-key, mixed>|null
   */
  public static function getErrorHandlerExceptionDetails(\Throwable $Exception): ?array {
    if(!AppEnv::displayInternalError()) {
      Debugger::tryLog($Exception, Debugger::EXCEPTION);
      return null;
    }

    return [
      "exception" => ExceptionDump::toArray($Exception)
    ];
  }


  /**
   * Získání hodnot z cesty $requestPath dle masky v url
   *
   * @return array<string, string>
   */
  public static function parsePathArgumentsForULRMask(string $urlMask, string $requestPath): array {
    $pattern = self::createRegexFromUrlMask($urlMask);
    if (preg_match($pattern, $requestPath, $matches)) {
      // Pouze pojmenované vysledky
      return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
    }

    return [];
  }


  /**
   * Vytvoření pregu pro masku URL
   *
   * /user/{hash:[a-zA-Z0-9]+}/ převede na pattern kde pojmenuje skupinu hash
   *
   * @param string $urlMask
   * @return string
   */
  public static function createRegexFromUrlMask(string $urlMask): string {
    // Wildcard *
    $url = preg_replace_callback(
      '/\*(?=(?:[^{}]*{[^{}]*})*[^{}]*$)/',
      function ($matches) {
        return "(?:.*)";
      },
      $urlMask
    ) ?: $urlMask;

    // Argumenty v {name:regex}
    $regex = preg_replace_callback('/\{(\w+):([^}]+)\}/', function($matches) {
      $name = preg_quote($matches[1], "~");
      $pattern = $matches[2];

      return "(?P<{$name}>{$pattern})";
    }, $url);

    return "~^{$regex}$~";
  }

}
