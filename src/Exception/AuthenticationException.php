<?php
declare(strict_types=1);

namespace App\Exception;


use App\Http\Enum\StatusCodeEnum;


class AuthenticationException extends ApiException {

  public function getStatusCodeEnum(): StatusCodeEnum  {
    return StatusCodeEnum::STATUS_UNAUTHORIZED;
  }

  public function getDetails(): string {
    return "Missing authentication key. Use bearer to sign in.";
  }

}