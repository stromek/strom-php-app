<?php
declare(strict_types=1);

namespace App\Http\Session\Handler;

interface SessionHandlerInterface {

  public function register(): bool;
}