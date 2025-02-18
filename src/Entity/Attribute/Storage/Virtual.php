<?php
declare(strict_types=1);

namespace App\Entity\Attribute\Storage;



#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Virtual implements StorageInterface {

  public function autoRefresh(): bool {
    return true;
  }

  public function isAutoIncrement(): bool {
    return false;
  }

  public function isVirtual(): bool {
    return true;
  }
}