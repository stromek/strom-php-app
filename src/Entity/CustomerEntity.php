<?php
declare(strict_types=1);

namespace App\Entity;

use OpenApi\Attributes as OA;
use App\Entity\Attribute\ApiResponse\Visibility;
use App\Entity\Attribute\Storage\Virtual;
use App\Entity\Attribute\Storage\Primary;
use App\Entity\Attribute\Validator\Length;
use App\Entity\Attribute\Validator\Range;
use App\Entity\Attribute\Value\DefaultValue;


/**
 * @extends Entity<CustomerEntity>
 * @property int $id
 * @property string $clientKey
 * @property string $name
 * @property bool $isActive
 * @property \DateTime $createdAt
 */
#[OA\Schema(
  schema: "Entity:Customer",
  type: "object"
)]
class CustomerEntity extends Entity {

  #[Range(1, null)]
  #[Primary(Primary::AUTO_INCREMENT)]
  #[Visibility(Visibility::HIDDEN)]
  private int $id;

  #[Length(50, 50)]
  #[Visibility(Visibility::HIDDEN)]
  #[Virtual]
  private string $clientKey;

//  #[NotEmpty]
  #[Length(min: 1, max: 100)]
  #[OA\Property(title: 'Customer name', example: "Company ltd.")]
  private string $name;

  #[OA\Property(description: "true = active account, false = disabled account")]
  private bool $isActive = true;

  #[DefaultValue(DefaultValue::NOW)]
//  #[Storage(Storage::AUTO_REFRESH)]
  #[OA\Property(ref: "#/components/schemas/DateTimeInterface")]
  private \DateTimeInterface $createdAt;

}
