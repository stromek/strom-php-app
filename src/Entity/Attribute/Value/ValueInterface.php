<?php
declare(strict_types=1);

namespace App\Entity\Attribute\Value;


use App\Entity\Entity;
use App\Entity\Attribute\AttributeInterface;


/**
 * @template T
 * @template E of Entity
 */
interface ValueInterface extends AttributeInterface {

  /**
   * @param T $oldValue
   * @param ?Entity<E> $Entity
   * @return mixed new value
   */
  public function generate(mixed $oldValue, ?Entity $Entity = null): mixed;
}