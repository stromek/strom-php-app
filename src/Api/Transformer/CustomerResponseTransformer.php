<?php
declare(strict_types=1);

namespace App\Api\Transformer;



class CustomerResponseTransformer implements ResponseTransformerInterface {
  
  public function transformCustomer(\App\Entity\CustomerEntity $Customer): array {
    return [
      "customer" => $Customer->toArray()
    ];
  }
}