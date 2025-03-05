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
   * @return array{version: ?string, entity: string, attributes: array<array-key, mixed>}
   */
  public function transformEntity(Entity $Entity, string $version = null): array {
    $response = [
      "version" => $version,
      "entity" => $Entity::class,
      "attributes" => []
    ];

    foreach($Entity->getProperties() as $Property) {
      $visibilityList = Arr::create($Property->getAttributes(Visibility::class));

      $isHidden = $visibilityList->find(fn(Visibility $value) => $value->isHidden($version));
      $isVisible = $visibilityList->find(fn(Visibility $value) => $value->isVisible($version));

      if($isHidden AND $isVisible) {
        throw new ResponseTransformerException("Property visibility '".$Entity::class."::".$Property->getName()."' cannot be set to 'visible' and 'hidden' at the same time .");
      }

      if($isHidden) { {
        continue;
      }}

      if($isVisible OR $visibilityList->count() === 0) {
        $response['attributes'][$Property->getName()] = $Property->getValueSafe();
      }
    }

    return $response;
  }


  /**
   * @template E of Entity
   * @param Entity<E>[] $entities
   * @return array<array-key, array{version: ?string, entity: string, attributes: array<array-key, mixed>}>
   */
  public function transformEntityList(array $entities, string $version = null): array {
    $response = [];

    foreach($entities as $Entity) {
      $response[] = $this->transformEntity($Entity, $version);
    }

    return $response;
  }
}