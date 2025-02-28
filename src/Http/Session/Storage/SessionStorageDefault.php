<?php
declare(strict_types=1);

namespace App\Http\Session\Storage;


class SessionStorageDefault implements SessionStorageInterface {

  public function getValue(string $key): mixed {
    return $_SESSION[$key] ?? null;
  }

  public function setValue(string $key, mixed $value): void {
    $_SESSION[$key] = $value;
  }

  public function removeValue(string $key): void {
    unset($_SESSION[$key]);
  }

  public function clear(): void {
    $_SESSION = [];
  }

}