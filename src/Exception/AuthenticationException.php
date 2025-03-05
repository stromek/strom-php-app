<?php
declare(strict_types=1);

namespace App\Exception;


use App\Http\Enum\StatusCodeEnum;


class AuthenticationException extends AppException {


  public function getStatusCodeEnum(): StatusCodeEnum  {
    return StatusCodeEnum::STATUS_UNAUTHORIZED;
  }

  
}