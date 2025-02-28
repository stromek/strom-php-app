<?php
declare(strict_types=1);

namespace App\Http\Session;


class SessionNamespace {

  private Session $session;

  private readonly string $namespace;

  public function __construct(Session $Session, string $namespace) {
    $this->session = $Session;
    $this->namespace = $namespace;
  }

  public function createNamespace(string $namespace): self {
    return new self($this->session, $this->namespace."/".$namespace);
  }


  public function get(string $name): mixed {
    return $this->session->getValue($this->makeKey($name));
  }


  public function set(string $name, mixed $value, int $ttl = 0): void {
     $this->session->setValue($this->makeKey($name), $value, $ttl);
  }


  public function remove(string $name): void {
    $this->session->removeValue($this->makeKey($name));
  }

  private function makeKey(string $name): string {
    return $this->namespace.":".$name;
  }

}