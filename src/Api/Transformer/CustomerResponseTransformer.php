<?php
declare(strict_types=1);

namespace App\Api\Transformer;



class CustomerResponseTransformer implements ResponseTransformerInterface {

  #[\DI\Attribute\Inject]
  private EntityResponseTransformer $entityResponseTransformer;

  public function transformCustomer(\App\Entity\CustomerEntity $Customer): array {
    return $this->entityResponseTransformer->transformEntity($Customer);
  }
}