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

    return [
      "class" => $Exception::class,
      "message" => $Exception->getMessage(),
      "code" => $Exception->getCode(),
      "file" => $Exception->getFile(),
      "line" => $Exception->getLine(),
      "trace" => $trace,
      "previous" => $Exception->getPrevious() ? self::toArray($Exception->getPrevious()) : null,
    ];
  }

}

