<?php
declare(strict_types=1);

namespace App\Entity\Attribute\Mutator;


use App\Entity\EntityInterface;


/**
 * @implements MutatorInterface<int|float>
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
   */
  public function mutate($value, ?EntityInterface $Entity = null): float {
    return round($value, $this->decimal, $this->roundMode);
  }

}