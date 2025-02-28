<?php
declare(strict_types=1);

namespace App\Http\Session\Handler;

class SessionHandlerDefault implements SessionHandlerInterface {

  public function register(): bool {
    return true;
  }

}