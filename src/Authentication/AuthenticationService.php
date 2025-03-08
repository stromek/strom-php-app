<?php
declare(strict_types=1);

namespace App\Authentication;

use App\Authentication\Provider\AuthProviderInterface;

use App\Entity\UserEntity;
use App\Entity\CustomerEntity;


class AuthenticationService {


  private AuthProviderInterface $provider;

  public function __construct(AuthProviderInterface $provider) {
    $this->provider = $provider;
  }


  public function authenticateCustomer(): ?CustomerEntity {
    $Customer = $this->provider->authenticateCustomer();

    return $Customer?->isActive ? $Customer : null;
  }
  

  public function authenticateUser(): ?UserEntity {
    return null;
  }

}