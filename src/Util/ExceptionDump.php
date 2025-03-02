<?php
declare(strict_types=1);

namespace App\Util;


abstract class ExceptionDump {

  /**
   * @param \Throwable $Exception
   * @return array{message: string, code: int, file: string, line: int, trace: array<int, mixed>, class: class-string}
   */
  public static function toArray(\Throwable $Exception): array {
    return [
      "class" => $Exception::class,
      "message" => $Exception->getMessage(),
      "code" => $Exception->getCode(),
      "file" => $Exception->getFile(),
      "line" => $Exception->getLine(),
      "trace" => $Exception->getTrace(),
      "previous" => $Exception->getPrevious() ? self::toArray($Exception->getPrevious()) : null,
    ];
  }

}

