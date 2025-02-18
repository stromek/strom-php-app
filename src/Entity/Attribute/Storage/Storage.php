<?php
declare(strict_types=1);

namespace App\Entity\Attribute\Storage;


#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Storage implements StorageInterface {

  const AUTO_INCREMENT = 1;

  const AUTO_REFRESH = 2;

  protected int $mode;

  public function __construct(int $mode = 0) {
    $this->mode = $mode;
  }

  public function autoRefresh(): bool {
    return boolval($this->mode & self::AUTO_REFRESH);
  }

  public function isAutoIncrement(): bool {
    return boolval($this->mode & self::AUTO_INCREMENT);
  }

  public function isVirtual(): bool {
    return false;
  }
}