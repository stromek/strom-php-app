<?php
declare(strict_types=1);

namespace App\Authentication\Provider;

use App\Api\Request\RequestInterface;
use App\Entity\CustomerEntity;
use App\Entity\UserEntity;
use App\Repository\CustomerAuthRepositoryMySQL;
use App\Repository\CustomerRepositoryMySQL;
use DI\Attribute\Inject;


class AuthDevelopProvider implements AuthProviderInterface {

  #[Inject]
  private readonly CustomerRepositoryMySQL $customerRepository;

  #[Inject]
  private readonly CustomerAuthRepositoryMySQL $customerAuthRepository;

  /**
   * @var RequestInterface<array-key, mixed>
   */
  private RequestInterface $request;

  private AuthBearerProvider $baseProvider;


  /**
   * @param RequestInterface<array-key, mixed> $Request
   */
  public function __construct(RequestInterface $Request, AuthBearerProvider $BaseProvider) {
    $this->request = $Request;
    $this->baseProvider = $BaseProvider;
  }


  public function authenticateCustomer(): ?CustomerEntity {
    $bearer = $this->getBearer();
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? "'";

    try {
      // Zakládní přihlášení (pro testování srkze swagger client)
      $Customer = $this->baseProvider->authenticateCustomer();
      if($Customer) {
        return $Customer;
      }


      if($bearer) {
        $CustomerAuth = $this->customerAuthRepository->findAuth(\App\Entity\Enum\CustomerAuthTypeEnum::HTTP_BEARER, $bearer);

        return $this->customerRepository->findByID($CustomerAuth->customer_id);
      }

      if($ipAddress) {
        $CustomerAuth = $this->customerAuthRepository->findAuth(\App\Entity\Enum\CustomerAuthTypeEnum::REMOTE_ADDRESS, $ipAddress);

        return $this->customerRepository->findByID($CustomerAuth->customer_id);
      }

    } catch(\App\Repository\RepositoryException $e) {
      if($e->getCode() === \App\Repository\RepositoryException::NOT_FOUND) {
        return null;
      }

      throw $e;
    }

    return null;
  }


  private function getBearer(): ?string {
    $bearer = $this->request->getQuery("bearer");
    return is_string($bearer) ? $bearer : null;
  }


  public function authenticateUser(): ?UserEntity {
    return $this->baseProvider->authenticateUser();
  }

}