<?php
declare(strict_types=1);


namespace App\Api\Router;



interface RouteDefinitionInterface {

  /**
   * Odchyceni chybi
   *
   * @param class-string<\Exception> $class
   * @return void
   */
  public function setErrorHandler(string $class, \Closure $Closure): void;


  public function addMiddleware(\App\Middleware\MiddlewareInterface $Middleware): void;


  public function group(string $url, \Closure $handler): RouteGroup;


  /**
   * @param \Closure|array{0: class-string, 1: string} $handler
   */
  public function get(string $url, \Closure|array $handler): Route;


  /**
   * @param \Closure|array{0: class-string, 1: string} $handler
   */
  public function post(string $url, \Closure|array $handler): Route;


  /**
   * @param \Closure|array{0: class-string, 1: string} $handler
   */
  public function put(string $url, \Closure|array $handler): Route;


  /**
   * @param \Closure|array{0: class-string, 1: string} $handler
   */
  public function patch(string $url, \Closure|array $handler): Route;


  /**
   * @param \Closure|array{0: class-string, 1: string} $handler
   */
  public function delete(string $url, \Closure|array $handler): Route;

}