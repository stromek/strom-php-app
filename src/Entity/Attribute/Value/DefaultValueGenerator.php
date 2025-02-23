<?php
declare(strict_types = 1);

namespace App\Entity\Attribute\Value;

use App\Entity\Entity;


/**
 * @template E of Entity
 * @implements ValueInterface<callable, E>
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
   * @param ?Entity<E> $Entity
   * @return mixed
   */
  public function generate(mixed $oldValue, ?Entity $Entity = null): mixed {
    return ($this->callback)();
  }


}