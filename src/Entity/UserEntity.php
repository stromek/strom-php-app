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
class UserEntity extends Entity {

  #[Range(1, null)]
  #[Primary(Primary::AUTO_INCREMENT)]
  #[Visibility(Visibility::HIDDEN)]
  private int $id;

  #[Range(1, null)]
  #[Visibility(Visibility::HIDDEN)]
  private int $customer_id;

  #[Length(1, 200)]
  private string $name;


  #[Length(1, 50)]
  private string $code;

  #[Length(10, 10)]
  #[Virtual]
  private string $hash;

  #[Length(null, 200)]
  private ?string $emailAddress;

  #[Length(null, 200)]
  private ?string $avatarURL;

  #[DefaultValue(DefaultValue::NOW)]
  private \DateTimeInterface $createdAt;


  public function getAvatarURL(): ?string {
    return $this->avatarURL ?? "https://gravatar.com/avatar/".md5(strval($this->id ?? 0))."?s=400&d=robohash&r=x";
  }
}
