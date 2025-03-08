<?php
declare(strict_types=1);

namespace App\Entity\Attribute\Mutator;


use App\Entity\EntityInterface;


/**
 * @implements MutatorInterface<int|float>
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Division implements MutatorInterface {


  private int|float $divisor;

  public function __construct(int|float $divisor) {
    $this->divisor = $divisor;
  }

  /**
   * @param int|float $value
   */
  public function mutate($value, ?EntityInterface $Entity = null): float {
    return $value / $this->divisor;
  }

}