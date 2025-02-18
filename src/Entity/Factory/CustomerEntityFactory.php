<?php
declare(strict_types=1);

namespace App\Entity\Factory;


use App\Entity\CustomerEntity;


class CustomerEntityFactory extends EntityFactory {

  /**
   * @param array<string, mixed> $attributes
   */
  public function createCustomer(array $attributes = []): CustomerEntity {
    return $this->createEntity(CustomerEntity::class, $attributes);
  }

}