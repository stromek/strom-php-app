<?php
declare(strict_types=1);

namespace App\Entity\Attribute\Validator;

use App\Entity\Entity;


/**
 * @template E of Entity
 * @extends Validator<string, E>
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class EmailAddress extends Validator {

  /**
   * @param string $value
   * @param ?Entity<E> $Entity
   * @throws ValidatorException
   */
  public function validate($value, ?Entity $Entity = null): void {
    if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
      throw new ValidatorException("E-mail address is not valid");
    }
  }
}