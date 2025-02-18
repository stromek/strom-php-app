<?php
declare(strict_types=1);

namespace App\Api\Transformer;


class BaseResponseTransformer implements ResponseTransformerInterface {

  /**
   * @return array<array-key, mixed>
   */
  public function transform(mixed $payload): array {
    return (array)$payload;
  }
}