<?php
declare(strict_types=1);

namespace App\Entity\Attribute\Validator;

use App\Entity\EntityInterface;


/**
 * @extends Validator<string>
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class EmailAddress extends Validator {

  /**
   * @param string $value
   * @throws ValidatorException
   */
  public function validate($value, ?EntityInterface $Entity = null): void {
    if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
      throw new ValidatorException("E-mail address is not valid");
    }
  }
}