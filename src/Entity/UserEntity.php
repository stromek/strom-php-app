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
 * @extends Entity<UserEntity>
 * @property int $id
 * @property int $customer_id
 * @property string $hash
 * @property string $code
 * @property string $name
 * @property ?string $emailAddress
 * @property ?string $avatarURL
 * @property \DateTime $createdAt
 */
#[OA\Schema(
  schema: "UserEntity",
  type: "object",
)]
#[OA\Schema(
  schema: "UserEntityList",
  allOf: [
    new OA\Schema(ref: "#/components/schemas/ResponseEntityList"),
    new OA\Schema(properties: [
      new OA\Property(property: "payload", type: "array", items: new OA\Items(properties: [
        new OA\Property(property: "attributes", ref: "#/components/schemas/UserEntity")
      ]))
    ])
  ]
)]
class UserEntity extends Entity {

  #[Range(1, null)]
  #[Primary(Primary::AUTO_INCREMENT)]
  #[Visibility(Visibility::HIDDEN)]
  private int $id;

  #[Range(1, null)]
  #[Visibility(Visibility::HIDDEN)]
  private int $customer_id;

  #[Length(1, 200)]
  #[OA\Property(example: "John Doe")]
  private string $name;


  #[Length(1, 50)]
  #[OA\Property(title: "Unikátní kód uživatele", example: "USER-1")]
  private string $code;

  #[Length(10, 10)]
  #[Virtual]
  #[OA\Property(title: "Unikátní hash uživatele", example: "yZ1eX5zXU1", ref: "#/components/schemas/UserEntityHash")]
  private string $hash;

  #[Length(null, 200)]
  #[OA\Property(example: "john.doe@example.com")]
  private ?string $emailAddress;

  #[Length(null, 200)]
  #[OA\Property(example: "https://gravatar.com/avatar/eccbc87e4b5ce2fe28308fd9f2a7baf3?s=400&d=robohash&r=x")]
  private ?string $avatarURL;

  #[DefaultValue(DefaultValue::NOW)]
  #[OA\Property(ref: "#/components/schemas/DateTimeInterface")]
  private \DateTimeInterface $createdAt;


  public function getAvatarURL(): ?string {
    return $this->avatarURL ?? "https://gravatar.com/avatar/".md5(strval($this->id ?? 0))."?s=400&d=robohash&r=x";
  }
}
