<?php
declare(strict_types=1);

namespace App\Tests\Mock\Entity;

use App\Entity\Attribute\Mutator\Decimal;
use App\Entity\Attribute\Mutator\Division;
use App\Entity\Attribute\Storage\Primary;
use App\Entity\Attribute\Validator\Length;
use App\Entity\Attribute\Validator\NotEmpty;
use App\Entity\Attribute\Validator\Range;
use App\Entity\Attribute\Value\DefaultValue;
use App\Entity\Entity;


/**
 * @property int $blank
 * @property int $id
 * @property int $code
 * @property string $name
 * @property \DateTimeInterface $datetime
 * @property \DateTimeInterface $date
 * @property float $decimal
 * @property float $dividend
 */
class EntityMock extends Entity {

  private int $blank;

  #[Range(1, null)]
  #[Primary]
  private int $id;

  private int $code = 10;

  #[NotEmpty]
  #[Length(min: 1, max: 100)]
  private string $name;

  #[DefaultValue(DefaultValue::NOW)]
  private \DateTimeInterface $datetime;

  #[DefaultValue(DefaultValue::CURDATE)]
  private \DateTimeInterface $date;


  #[Range(1, 100)]
  #[Decimal(2)]
  private float $decimal;

  // Vícenásobné dělení
  #[Division(3)]
  #[Decimal(0)]
  #[Division(2)]
  private float $dividend;
}