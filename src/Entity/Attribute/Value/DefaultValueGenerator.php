<?php
declare(strict_types = 1);

namespace App\Entity\Attribute\Value;

use App\Entity\EntityInterface;


/**
 * @implements ValueInterface<callable>
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class DefaultValueGenerator implements ValueInterface {

  private \Closure $callback;

  public function __construct(callable $callback, mixed ...$args) {
    $this->callback = function() use ($callback, $args) {
      return $callback(...$args);
    };
  }


  /**
   * @param mixed $oldValue
   * @return mixed
   */
  public function generate(mixed $oldValue, ?EntityInterface $Entity = null): mixed {
    return ($this->callback)();
  }


}