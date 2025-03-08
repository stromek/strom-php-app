<?php
declare(strict_types=1);

namespace App\Entity\Attribute\Validator;


use App\Entity\EntityInterface;


/**
 * @extends Validator<int|float>
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Range extends Validator {

  private null|int|float $min;

  private null|int|float $max;


  public function __construct(null|int|float $min, null|int|float $max) {
    $this->min = $min;
    $this->max = $max;

    if(is_null($min) AND is_null($max)) {
      throw new \InvalidArgumentException('At least one value (min/max) must be a number or float');
    }
  }


  /**
   * @param int|float $value
   * @throws ValidatorException
   */
  public function validate($value, ?EntityInterface $Entity = null): void {
    if((!is_null($this->min) AND $value < $this->min) OR (!is_null($this->max) AND $value > $this->max)) {
      throw new ValidatorException("The value '{$value}' must be between '".($this->min??"[unlimited]")."' and '".($this->max??"[unlimited]")."'.");
    }
  }
}