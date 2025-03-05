<?php
declare(strict_types=1);

namespace App\Authentication\Provider;

interface AuthProviderInterface {


  public function authenticateCustomer(): ?\App\Entity\CustomerEntity;

  
  public function authenticateUser(): ?\App\Entity\UserEntity;

}