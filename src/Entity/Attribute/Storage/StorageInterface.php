<?php
declare(strict_types=1);

namespace App\Entity\Attribute\Storage;



use App\Entity\Attribute\AttributeInterface;


interface StorageInterface extends AttributeInterface {

  public function autoRefresh(): bool;

  public function isAutoIncrement(): bool;

  public function isVirtual(): bool;
}