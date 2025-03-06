<?php
declare(strict_types=1);

namespace App\Entity;

use OpenApi\Attributes as OA;
use App\Entity\Attribute\ApiResponse\Visibility;
use App\Entity\Attribute\Storage\Primary;
use App\Entity\Attribute\Storage\Virtual;
use App\Entity\Attribute\Validator\Length;
use App\Entity\Attribute\Validator\Range;
use App\Entity\Attribute\Value\DefaultValue;


/**
 * @extends Entity<ThreadEntity>
 * @property int $id
 * @property int $customer_id
 * @property string $name
 * @property string $code
 * @property ?string $url
 * @property string $hash
 * @property \DateTime $createdAt
 */
#[OA\Schema(
  schema: "Entity:Thread",
  type: "object"
)]
#[OA\Schema(
  schema: "Entity:Thread:Hash",
  type: "string",
  pattern: "^[a-zA-Z0-9]{10}\$",
  example: "eVO6SY5kf5"
)]
class ThreadEntity extends Entity {

  #[Range(1, null)]
  #[Primary(Primary::AUTO_INCREMENT)]
  #[Visibility(Visibility::HIDDEN)]
  private int $id;

  #[Range(1, null)]
  #[Visibility(Visibility::HIDDEN)]
  private int $customer_id;

  #[Length(1, 100)]
  #[OA\Property(description: "Unikátní kód threadu dle aplikace", example: "ORDER-1234-A")]
  private string $code;

  #[Length(1, 200)]
  #[OA\Property(description: "Název threadu", example: "Objednávka 1234")]
  private string $name;


  #[Length(null, 255)]
  #[OA\Property(description: "URL k objektu ke kterému se thread vztahuje (např. odkaz na detail objednávky)", example: "http://www.example.com/order/1234/")]
  private ?string $url;

  #[Length(10, 10)]
  #[Virtual]
  #[OA\Property(ref: "#/components/schemas/Entity:Thread:Hash", description: "Unikátní hash threadu", example: "eVO6SY5kf5")]
  private ?string $hash;

  #[DefaultValue(DefaultValue::NOW)]
  #[OA\Property(ref: "#/components/schemas/DateTimeInterface")]
  private \DateTimeInterface $createdAt;

}
