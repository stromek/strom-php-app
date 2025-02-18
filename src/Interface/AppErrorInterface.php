<?php
declare(strict_types=1);

namespace App\Interface;


use App\Http\Enum\StatusCodeEnum;


interface AppErrorInterface {

  public function getStatusCodeEnum(): StatusCodeEnum;
}