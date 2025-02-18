<?php
declare(strict_types=1);

namespace App\Entity\Attribute\Mutator;


use App\Entity\Entity;


/**
 * @template T
 * @template E of Entity
 * @implements MutatorInterface<int|float, E>
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Decimal implements MutatorInterface {

  private int $decimal;

  /**
   * @var int<1, 4>
   */
  private int $roundMode;

  /**
   * @param int $decimal
   * @param int<1, 4> $roundMode
   **/
  public function __construct(int $decimal, int $roundMode = \PHP_ROUND_HALF_UP) {
    $this->decimal = $decimal;
    $this->roundMode = $roundMode;
  }

  /**
   * @param int|float $value
   * @param ?Entity<E> $Entity
   */
  public function mutate($value, ?Entity $Entity = null): float {
    return round($value, $this->decimal, $this->roundMode);
  }

}