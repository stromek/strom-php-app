<?php
declare(strict_types=1);

namespace App\Api\Router;

use App\Api\Request\RequestInterface;


class RouteFilterPayload {

  /**
   * @var RequestInterface<array-key, mixed>|null
   */
  public readonly ?RequestInterface $request;

  public ?\Throwable $exception = null;

  public ?Route $route;


  /**
   * @param RequestInterface<array-key, mixed> $Request
   */
  public function __construct(RequestInterface $Request) {
    $this->request = $Request;
  }

}