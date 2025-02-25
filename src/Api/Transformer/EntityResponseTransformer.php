<?php
declare(strict_types=1);

namespace App\Api\Transformer;



use App\Entity\Attribute\ApiResponse\Visibility;
use App\Entity\Entity;
use App\Util\Arr;


class EntityResponseTransformer implements ResponseTransformerInterface {

  /**
   * @template E of Entity
   * @param Entity<E> $Entity
   * @return array<array-key, mixed>
   */
  public function transformEntity(Entity $Entity, string $version = null): array {
    $response = [
    ];

    foreach($Entity->getProperties() as $Property) {
      $visibilityList = Arr::create($Property->getAttributes(Visibility::class));

      $isHidden = $visibilityList->find(fn(Visibility $value) => $value->isHidden($version));
      $isVisible = $visibilityList->find(fn(Visibility $value) => $value->isVisible($version));

      if($isHidden) {
        continue;
      }

      if($isVisible OR $visibilityList->count() === 0) {
        $response[$Property->getName()] = $Property->getValueSafe();
      }
    }

    return $response;
  }
}