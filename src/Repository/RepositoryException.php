<?php
declare(strict_types=1);

namespace App\Repository;

use App\Http\Enum\StatusCodeEnum;


class RepositoryException extends \Exception implements \App\Interface\AppErrorInterface {


  const NOT_FOUND = 404;

  const DELETE_FAILED = 500;

  const UPDATE_FAILED = 500;

  const INSERT_FAILED = 500;

  /**
   * @param string $message
   * @param self::* $code
   * @param \Throwable|null $previous
   */
  public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null) {
    parent::__construct($message, $code, $previous);
  }


  public function getStatusCodeEnum(): StatusCodeEnum {
    return match ($this->code) {
      self::NOT_FOUND => StatusCodeEnum::STATUS_NOT_FOUND,
      default => StatusCodeEnum::STATUS_INTERNAL_SERVER_ERROR,
    };
  }

}