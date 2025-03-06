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

}
