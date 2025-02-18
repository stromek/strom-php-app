<?php
declare(strict_types=1);

namespace App\Entity\Attribute\Validator;


use App\Entity\Entity;


/**
 * @template E of Entity
 * @extends Validator<string, Entity>
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Length extends Validator {

  private null|int $min;

  private null|int $max;


  public function __construct(null|int $min, null|int $max) {
    $this->min = $min;
    $this->max = $max;

    if(is_null($min) AND is_null($max)) {
      throw new \InvalidArgumentException('At least one value (min/max) must be a number or float');
    }
  }


  /**
   * @param string $value
   * @param ?Entity<E> $Entity
   * @throws ValidatorException
   */
  public function validate($value, ?Entity $Entity = null): void {
    $length = mb_strlen(strval($value));

    if((!is_null($this->min) AND $length < $this->min) OR (!is_null($this->max) AND $length > $this->max)) {
      throw new ValidatorException("The length must be between '".($this->min??"[unlimited]")."' and '".($this->max??"[unlimited]")."', '{$length}' given.");
    }
  }
}