<?php
declare(strict_types=1);

namespace App\Entity;

use OpenApi\Attributes as OA;
use App\Entity\Attribute\ApiResponse\Visibility;
use App\Entity\Attribute\Storage\Storage;
use App\Entity\Attribute\Storage\Primary;
use App\Entity\Attribute\Validator\Length;
use App\Entity\Attribute\Validator\NotEmpty;
use App\Entity\Attribute\Validator\Range;
use App\Entity\Attribute\Value\DefaultValue;
use App\Entity\Attribute\Value\DefaultValueGenerator;


/**
 * @extends Entity<CustomerEntity>
 * @property int $id
 * @property string $name
 * @property \DateTime $createdAt
 */
#[OA\Schema()]
class CustomerEntity extends Entity {

  #[Range(1, null)]
  #[Primary(Primary::AUTO_INCREMENT)]
  #[OA\Property(
    property: "id",
    type: "integer",
    description: "The ID of the Customer.",
  )]
  private int $id;

//  #[NotEmpty]
  #[Length(min: 1, max: 100)]
  private string $name;

  #[DefaultValue(DefaultValue::NOW)]
//  #[Storage(Storage::AUTO_REFRESH)]
  private \DateTimeInterface $createdAt;

}
