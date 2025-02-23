<?php
declare(strict_types=1);

namespace App\Entity\Attribute\Mutator;


use App\Entity\Entity;


/**
 * @template E of Entity
 * @implements MutatorInterface<int|float, E>
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Division implements MutatorInterface {


  private int|float $divisor;

  public function __construct(int|float $divisor) {
    $this->divisor = $divisor;
  }

  /**
   * @param int|float $value
   * @param ?Entity<E> $Entity
   */
  public function mutate($value, ?Entity $Entity = null): float {
    return $value / $this->divisor;
  }

}