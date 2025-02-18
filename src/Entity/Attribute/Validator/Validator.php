<?php
declare(strict_types=1);

namespace App\Entity\Attribute\Validator;

use App\Entity\Entity;


/**
 * @template T
 * @template E of Entity
 * @implements ValidatorInterface<T, E>
 */
abstract class Validator implements ValidatorInterface {

  /**
   * @param T $value
   * @param ?Entity<E> $Entity
   */
  public function isValid($value, ?Entity $Entity = null): bool {
    try {
      $this->validate($value, $Entity);
      return true;
    } catch(ValidatorException $e) {
      return false;
    }
  }
}