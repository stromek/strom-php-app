<?php
declare(strict_types=1);

namespace App\Entity\Attribute\Validator;

use App\Entity\Entity;
use App\Entity\Attribute\AttributeInterface;


/**
 * @template T
 * @template E of Entity
 */
interface ValidatorInterface extends AttributeInterface {


  /**
   * @param T $value
   * @param ?Entity<E> $Entity
   * @throws ValidatorException
   */
  public function validate($value, ?Entity $Entity = null): void;


  /**
   * @param T $value
   * @param ?Entity<E> $Entity
   * @return bool
   */
  public function isValid($value, ?Entity $Entity = null): bool;
}