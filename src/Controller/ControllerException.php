<?php
declare(strict_types=1);

namespace App\Controller;

use App\Http\Enum\StatusCodeEnum;


class ControllerException extends \Exception implements \App\Interface\AppErrorInterface {



  public function getStatusCodeEnum(): StatusCodeEnum {
    return StatusCodeEnum::STATUS_INTERNAL_SERVER_ERROR;
//    return match ($this->code) {
//      default => StatusCodeEnum::STATUS_INTERNAL_SERVER_ERROR,
//    };
  }

}