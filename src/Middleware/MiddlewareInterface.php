<?php
declare(strict_types=1);

namespace App\Middleware;


use App\Api\Request\RequestInterface;


interface MiddlewareInterface {

  /**
   * @param RequestInterface<array-key, mixed> $Request
   * @param callable $next
   * @return void
   */
  public function handle(RequestInterface $Request, callable $next): void;

}