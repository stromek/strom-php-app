<?php
declare(strict_types=1);

namespace App\Entity\Attribute\Value;


use App\Entity\EntityInterface;
use App\Entity\Attribute\AttributeInterface;


/**
 * @template T
 */
interface ValueInterface extends AttributeInterface {

  /**
   * @param T $oldValue
   * @return mixed new value
   */
  public function generate(mixed $oldValue, ?EntityInterface $Entity = null): mixed;
}