<?php
declare(strict_types=1);

namespace App\Http\Session\Storage;


class SessionStorageMemory implements SessionStorageInterface {

  /**
   * @var array<array-key, mixed>
   */
  private array $data = [];

  public function getValue(string $key): mixed {
    return $this->data[$key] ?? null;
  }

  public function setValue(string $key, mixed $value): void {
    $this->data[$key] = $value;
  }

  public function removeValue(string $key): void {
    unset($this->data[$key]);
  }

  public function clear(): void {
    $this->data = [];
  }

}