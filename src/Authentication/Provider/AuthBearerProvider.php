<?php
declare(strict_types=1);

namespace App\Authentication\Provider;

use App\Api\Request\RequestInterface;
use App\Entity\CustomerEntity;
use App\Entity\UserEntity;
use App\Repository\CustomerAuthRepositoryMySQL;
use App\Repository\CustomerRepositoryMySQL;
use DI\Attribute\Inject;


class AuthBearerProvider implements AuthProviderInterface {

  #[Inject]
  private readonly CustomerRepositoryMySQL $customerRepository;

  #[Inject]
  private readonly CustomerAuthRepositoryMySQL $customerAuthRepository;

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
      $CustomerAuth = $this->customerAuthRepository->findAuth(\App\Entity\Enum\CustomerAuthTypeEnum::HTTP_BEARER, $bearer);
      
      return $this->customerRepository->findByID($CustomerAuth->customer_id);

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

    return null;
  }


  public function authenticateUser(): ?UserEntity {
    return null;
  }

}