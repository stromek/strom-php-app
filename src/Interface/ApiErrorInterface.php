<?php
declare(strict_types=1);

namespace App\Interface;



interface ApiErrorInterface extends \Throwable {


  public function getUserCode(): string;


  public function getUserMessage(): string;


  public function getDetails(): mixed;
}