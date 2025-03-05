<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Api\Request\RequestInterface;
use App\Authentication\AuthenticationService;
use App\Exception\AuthenticationException;


/**
 * @phpstan-type RequestCustomer array{id: int, name: string}
 */
class AuthenticationCustomerMiddleware implements MiddlewareInterface {

  private AuthenticationService $authenticationService;

  public function __construct(AuthenticationService $AuthenticationService) {
    $this->authenticationService = $AuthenticationService;

  }

  /**
   * @param RequestInterface<array-key, mixed> $Request
   */
  public function handle(RequestInterface $Request, callable $next): void {
    $Customer = $this->authenticationService->authenticateCustomer();

    if(!$Customer) {
      throw new AuthenticationException("Authenticated failed");
    }

    $Request['customer'] = [
      "id" => $Customer->id,
      "name" => $Customer->name
    ];

    $next();
  }

}