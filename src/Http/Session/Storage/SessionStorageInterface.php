<?php
declare(strict_types=1);

namespace App\Http\Session\Storage;


interface SessionStorageInterface {


  public function getValue(string $key): mixed;

  public function setValue(string $key, mixed $value): void;

  public function removeValue(string $key): void;

  public function clear(): void;

}