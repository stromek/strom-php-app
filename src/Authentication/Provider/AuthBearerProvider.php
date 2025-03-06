<?php
declare(strict_types=1);

namespace App\Authentication\Provider;

use App\Api\Request\RequestInterface;
use App\Entity\CustomerEntity;
use App\Entity\UserEntity;
use DI\Attribute\Inject;


class AuthBearerProvider implements AuthProviderInterface {

  #[Inject]
  private readonly \App\Repository\CustomerRepositoryMySQL $customerRepository;

  /**
   * @var RequestInterface<array-key, mixed>
   */
  private RequestInterface $request;


  /**
   * @param RequestInterface<array-key, mixed> $Request
   */
  public function __construct(RequestInterface $Request) {
    $this->request = $Request;
  }


  public function authenticateCustomer(): ?CustomerEntity {
    $bearer = $this->getBearer();
    if(!$bearer) {
      return null;
    }

    try {
      return $this->customerRepository->findByAuthToken($bearer);
    } catch(\App\Repository\RepositoryException $e) {
      if($e->getCode() === \App\Repository\RepositoryException::NOT_FOUND) {
        return null;
      }

      throw $e;
    }
  }


  private function getBearer(): ?string {
    $bearerPrefix = "Bearer ";

    $authorization = $this->request->getHeaderLine("authorization");
    if($authorization AND str_starts_with($authorization, $bearerPrefix)) {
      return substr($authorization, strlen($bearerPrefix));
    }

    // query param fallback
    $bearer = $this->request->getQuery("bearer");
    return is_string($bearer) ? $bearer : null;
  }


  public function authenticateUser(): ?UserEntity {
    return null;
  }

}