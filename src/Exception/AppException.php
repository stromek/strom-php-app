<?php
declare(strict_types=1);

namespace App\Exception;


use App\Http\Enum\StatusCodeEnum;


class AppException extends \Exception implements \App\Interface\AppErrorInterface {

  public function getStatusCodeEnum(): StatusCodeEnum {
    return StatusCodeEnum::STATUS_INTERNAL_SERVER_ERROR;
  }
}