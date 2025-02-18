<?php
declare(strict_types=1);

namespace App\Entity\Attribute\Mutator;

use App\Entity\Entity;
use App\Entity\Attribute\AttributeInterface;


/**
 * @template T
 * @template E of Entity
 */
interface MutatorInterface extends AttributeInterface {

  /**
   * @param T $value
   * @param ?Entity<E> $Entity
   */
  public function mutate($value, ?Entity $Entity = null): mixed;

}