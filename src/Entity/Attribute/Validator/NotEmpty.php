<?php
declare(strict_types=1);

namespace App\Entity\Attribute\Validator;

use App\Entity\EntityInterface;


/**
 * @extends Validator<string>
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class NotEmpty extends Validator {

  /**
   * @param string $value
   * @throws ValidatorException
   */
  public function validate($value, ?EntityInterface $Entity = null): void {
    if(!mb_strlen(strval($value))) {
      throw new ValidatorException("Value '{$value}' must not be empty.");
    }
  }
}