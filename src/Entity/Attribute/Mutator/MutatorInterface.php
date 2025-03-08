<?php
declare(strict_types=1);

namespace App\Entity\Attribute\Mutator;

use App\Entity\EntityInterface;
use App\Entity\Attribute\AttributeInterface;


/**
 * @template T
 */
interface MutatorInterface extends AttributeInterface {

  /**
   * @param T $value
   */
  public function mutate($value, ?EntityInterface $Entity = null): mixed;

}