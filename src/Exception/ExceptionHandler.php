<?php
declare(strict_types=1);

namespace App\Exception;


class ExceptionHandler {

  /**
   * @var array<class-string<\Throwable>, array<int, array{handler: \Closure, filter: ?\Closure}>>
   */
  private array $errorHandlers = [];


  /**
   * @param class-string<\Throwable> $class
   */
  public function addErrorHandler(string $class, \Closure $Closure, ?\Closure $Filter = null): void {
    $this->errorHandlers[$class] ??= [];
    $this->errorHandlers[$class][] = [
      "handler" => $Closure,
      "filter" => $Filter,
    ];
  }


  /**
   * @param class-string<\Throwable> $class
   * @return bool
   */
  public function hasErrorHandler(string $class): bool {
    return isset($this->errorHandlers[$class]);
  }


  /**
   * @param \Throwable $Exception
   * @param array<int, mixed> $filterArguments
   * @param array<int, mixed> $handlerArguments
   */
  public function handle(\Throwable $Exception, array $filterArguments = [], array $handlerArguments = []): mixed {
    foreach($this->errorHandlers as $class => $handlers) {
      // Neni shoda třidy vyjímky
      if($class !== $Exception::class AND !is_subclass_of($Exception::class, $class)) {
        continue;
      }

      foreach($handlers as ["handler" => $handler, "filter" => $filter]) {
        if(!$filter OR $filter(...$filterArguments)) {
          return $handler(...$handlerArguments);
        }
      }
    }

    throw new ExceptionHandlerException("Uncaught exception '".$Exception::class."' (".$Exception->getMessage()."). Use ".self::class."::addErrorHandler() to catch exception.", 0, $Exception);
  }
}