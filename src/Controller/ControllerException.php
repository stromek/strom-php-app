<?php
declare(strict_types=1);

namespace App\Controller;

use App\Http\Enum\StatusCodeEnum;


class ControllerException extends \App\Exception\AppException {


  public function getStatusCodeEnum(): StatusCodeEnum {
    return StatusCodeEnum::STATUS_INTERNAL_SERVER_ERROR;
  }

}