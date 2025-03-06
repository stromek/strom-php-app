<?php
declare(strict_types=1);

namespace App\Repository;

use App\Http\Enum\StatusCodeEnum;


class RepositoryException extends \App\Exception\ApiException {

  const UNKNOWN = 0;

  const NOT_FOUND = 1;

  const DELETE_FAILED = 2;

  const UPDATE_FAILED = 3;

  const INSERT_FAILED = 4;

  /**
   * @param string $message
   * @param self::* $code
   * @param \Throwable|null $previous
   */
  public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null) {
    parent::__construct($message, $code, $previous);
  }

  public function getStatusCodeEnum(): StatusCodeEnum {
    return match($this->code) {
      self::NOT_FOUND => StatusCodeEnum::STATUS_NOT_FOUND,
      default => parent::getStatusCodeEnum()
    };
  }


}