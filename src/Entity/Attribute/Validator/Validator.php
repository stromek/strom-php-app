<?php
declare(strict_types=1);

namespace App\Entity\Attribute\Validator;

use App\Entity\EntityInterface;


/**
 * @template T
 * @implements ValidatorInterface<T>
 */
abstract class Validator implements ValidatorInterface {

  /**
   * @param T $value
   */
  public function isValid($value, ?EntityInterface $Entity = null): bool {
    try {
      $this->validate($value, $Entity);
      return true;
    } catch(ValidatorException $e) {
      return false;
    }
  }
}