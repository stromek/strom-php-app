<?php
declare(strict_types=1);

namespace App\Entity\Factory;


use App\Entity\CustomerAuthEntity;


class CustomerAuthEntityFactory extends EntityFactory {

  /**
   * @param array<string, mixed> $attributes
   */
  public function createCustomerAuth(array $attributes = []): CustomerAuthEntity {
    return $this->createEntity(CustomerAuthEntity::class, $attributes);
  }

}