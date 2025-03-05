<?php
declare(strict_types=1);

namespace App\Entity;

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
  private string $hash;

  #[NotEmpty]
  private string $message;

  #[DefaultValue(DefaultValue::NOW)]
  private \DateTimeInterface $createdAt;

}
