<?php
declare(strict_types=1);

namespace App\Util;


abstract class ExceptionDump {

  /**
   * @param \Throwable $Exception
   * @return array{message: string, code: int, file: string, line: int, trace: array<int, mixed>, class: class-string}
   */
  public static function toArray(\Throwable $Exception): array {
    $trace = Arr::create($Exception->getTrace())
      ->map(fn($Trace) => Arr::create($Trace)->cherryPickKey(["file", "line", "function", "class", "type"])->toArray())
      ->toArray();

    $details = match(true) {
      $Exception instanceof \Dibi\DriverException => $Exception->getSql(),
      default => null
    };

    return [
      "class" => $Exception::class,
      "message" => $Exception->getMessage(),
      "code" => $Exception->getCode(),
      "file" => $Exception->getFile(),
      "line" => $Exception->getLine(),
      "details" => $details,
      "trace" => $trace,
      "previous" => $Exception->getPrevious() ? self::toArray($Exception->getPrevious()) : null,
    ];
  }

}

