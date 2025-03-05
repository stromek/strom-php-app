<?php
declare(strict_types=1);

namespace App\Exception;


use App\Http\Enum\StatusCodeEnum;
use App\Interface\AppErrorInterface;


class AppException extends \Exception implements AppErrorInterface {


  public function getStatusCodeEnum(): StatusCodeEnum {

    $Previous = $this->getPrevious();
    if($Previous instanceof AppErrorInterface) {
      return $Previous->getStatusCodeEnum();
    }

    return StatusCodeEnum::STATUS_INTERNAL_SERVER_ERROR;
  }

  
}