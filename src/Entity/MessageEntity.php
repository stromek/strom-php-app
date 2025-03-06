<?php
declare(strict_types=1);

namespace App\Entity;

use OpenApi\Attributes as OA;
use App\Entity\Attribute\ApiResponse\Visibility;
use App\Entity\Attribute\Storage\Primary;
use App\Entity\Attribute\Storage\Virtual;
use App\Entity\Attribute\Validator\NotEmpty;
use App\Entity\Attribute\Validator\Range;
use App\Entity\Attribute\Value\DefaultValue;


/**
 * @extends Entity<MessageEntity>
 * @property int $id
 * @property int $thread_id
 * @property int $customer_id
 * @property int $user_id
 * @property string $hash
 * @property string $message
 * @property \DateTime $createdAt
 */
#[OA\Schema(
  schema: "Entity:Message",
  properties: [
    new OA\Property(type: "string", property: "thread_hash"),
    new OA\Property(type: "string", property: "user_hash")
  ],
  type: "object"
)]
#[OA\Schema(
  schema: "Entity:Message:Hash",
  type: "string",
  pattern: "^[a-zA-Z0-9]{10}\$",
  example: "eVO6SY5kf5"
)]
class MessageEntity extends Entity {

  #[Range(1, null)]
  #[Primary(Primary::AUTO_INCREMENT)]
  #[Visibility(Visibility::HIDDEN)]
  private int $id;

  #[Range(1, null)]
  #[Visibility(Visibility::HIDDEN)]
  private int $thread_id;

  #[Range(1, null)]
  #[Visibility(Visibility::HIDDEN)]
  private int $customer_id;

  #[Range(1, null)]
  #[Visibility(Visibility::HIDDEN)]
  private int $user_id;

  #[Length(10, 10)]
  #[Virtual]
  #[OA\Property(ref: "#/components/schemas/Entity:Message:Hash", description: "Unikátní hash zprávy v rámci threadu", example: "yhzTz2ie5z")]
  private string $hash;

  #[NotEmpty]
  #[OA\Property(description: "Obsah zpravy ve formátu HTML", example: "<p>Toto je zpráva</p>")]
  private string $message;

  #[DefaultValue(DefaultValue::NOW)]
  #[OA\Property(ref: "#/components/schemas/DateTimeInterface")]
  private \DateTimeInterface $createdAt;

}
