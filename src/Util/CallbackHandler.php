<?php
declare(strict_types=1);

namespace App\Util;

use Psr\Container\ContainerInterface;


class CallbackHandler {

  private \Closure $callback;

  private ?ContainerInterface $container;

  private \ReflectionFunctionAbstract $reflection;

  /**
   * @param \Closure|array{0: class-string|object, 1: string}|string $callback
   */
  public function __construct(\Closure|array|string $callback, ?ContainerInterface $container = null) {
    $this->container = $container;

    $this->callback = match(true) {
      is_string($callback) => $this->createCallbackFromString($callback),
      is_array($callback) => $this->createCallbackFromArray($callback),
      default => $callback
    };
  }

  protected function getReflection(): \ReflectionFunctionAbstract {
    return $this->reflection;
  }


  private function createCallbackFromString(string $callback): \Closure {
    if(!is_callable($callback)) {
      throw new \InvalidArgumentException("Callback string '{$callback}' must be callable");
    }

    $this->reflection = new \ReflectionFunction($callback);
    return function(...$args) use ($callback) {
      return $callback(...$args);
    };
  }


  /**
   * @param array{0: class-string|object, 1: string} $array
   * @return \Closure
   */
  private function createCallbackFromArray(array $array): \Closure {
    $className = $array[0];
    $methodName = $array[1];

    if(is_object($className)) {
      if(!is_callable([$className, $methodName])) {
        throw new \InvalidArgumentException("Callback array '".$className::class."::{$methodName}' must be callable");
      }

      $this->reflection = new \ReflectionMethod($className, $methodName);
      return function(...$args) use ($className, $methodName) {
        return $className->{$methodName}(...$args);
      };
    }

    if(!class_exists($className)) {
      throw new \InvalidArgumentException("Callback array[0] '{$className}' must be class. Class not found.");
    }

    if(is_callable([$className, $methodName])) {
      $this->reflection = new \ReflectionMethod($className, $methodName);
      return function(...$args) use ($className, $methodName): mixed {
        return $className->{$methodName}(...$args);
      };
    }

    if($this->container AND $this->container->has($className)) {
      $Instance = $this->container->get($className);
      $this->reflection = new \ReflectionMethod($Instance, $methodName);

      return function(...$args) use ($Instance, $className, $methodName): mixed {
        return $Instance->get($className)->{$methodName}(...$args);
      };
    }

    if(!class_exists($className)) {
      throw new \InvalidArgumentException("'{$className}' not exists. Class not found.");
    }

    throw new \InvalidArgumentException("'{$className}::{$methodName}' is not callable");
  }


  /**
   * @param mixed ...$args
   */
  public function __invoke(...$args): mixed {
    $closure = $this->callback;

    return $closure(...$args);
  }

}