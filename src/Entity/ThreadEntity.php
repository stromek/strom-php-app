<?php
declare(strict_types=1);

namespace App\Entity;

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
class ThreadEntity extends Entity {

  #[Range(1, null)]
  #[Primary(Primary::AUTO_INCREMENT)]
  #[Visibility(Visibility::HIDDEN)]
  private int $id;

  #[Range(1, null)]
  #[Visibility(Visibility::HIDDEN)]
  private int $customer_id;

  #[Length(1, 100)]
  private string $code;

  #[Length(1, 200)]
  private string $name;


  #[Length(null, 255)]
  private ?string $url;

  #[Length(100, 100)]
  #[Virtual]
  private ?string $hash;

  #[DefaultValue(DefaultValue::NOW)]
  private \DateTimeInterface $createdAt;

}
