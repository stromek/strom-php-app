<?php
declare(strict_types=1);

namespace App\Entity\Attribute\Validator;

use App\Entity\EntityInterface;
use App\Entity\Attribute\AttributeInterface;


/**
 * @template T
 */
interface ValidatorInterface extends AttributeInterface {


  /**
   * @param T $value
   * @throws ValidatorException
   */
  public function validate($value, ?EntityInterface $Entity = null): void;


  /**
   * @param T $value
   */
  public function isValid($value, ?EntityInterface $Entity = null): bool;
}