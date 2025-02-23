<?php
declare(strict_types=1);

namespace App\Entity\Attribute\Validator;

use App\Entity\Entity;


/**
 * @template E of Entity
 * @extends Validator<string, E>
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class NotEmpty extends Validator {

  /**
   * @param string $value
   * @param ?Entity<E> $Entity
   * @throws ValidatorException
   */
  public function validate($value, ?Entity $Entity = null): void {
    if(!mb_strlen(strval($value))) {
      throw new ValidatorException("Value '{$value}' must not be empty.");
    }
  }
}