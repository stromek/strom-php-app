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
  schema: "Entity:User",
  type: "object",
)]
#[OA\Schema(
  schema: "Entity:User:Hash",
  type: "string",
  pattern: "^[a-zA-Z0-9]{10}\$",
  example: "dZ8x9XcVTx"
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
  #[OA\Property(title: "User name", example: "John Doe")]
  private string $name;


  #[Length(1, 50)]
  #[OA\Property(title: "Unique code of user in customer", example: "USER-1")]
  private string $code;

  #[Length(10, 10)]
  #[Virtual]
  #[OA\Property(title: "Hash", example: "yZ1eX5zXU1", ref: "#/components/schemas/Entity:User:Hash")]
  private string $hash;

  #[Length(null, 200)]
  #[OA\Property(title: "Email address", example: "john.doe@example.com")]
  private ?string $emailAddress;

  #[Length(null, 200)]
  #[OA\Property(title: "URL of avatar", example: "https://gravatar.com/avatar/eccbc87e4b5ce2fe28308fd9f2a7baf3?s=400&d=robohash&r=x")]
  private ?string $avatarURL;

  #[DefaultValue(DefaultValue::NOW)]
  #[OA\Property(ref: "#/components/schemas/DateTimeInterface")]
  private \DateTimeInterface $createdAt;


  public function getAvatarURL(): ?string {
    return $this->avatarURL ?? "https://gravatar.com/avatar/".md5(strval($this->id ?? 0))."?s=400&d=robohash&r=x";
  }
}
