<?php
declare(strict_types=1);

namespace App\Entity;

use App\Entity\Attribute\Storage\Primary;
use App\Entity\Attribute\Validator\Length;
use App\Entity\Attribute\Validator\Range;
use App\Entity\Enum\CustomerAuthTypeEnum;


/**
 * @extends Entity<CustomerAuthEntity>
 * @property int $customer_id
 * @property CustomerAuthTypeEnum $authType
 * @property string $authValue
 */
class CustomerAuthEntity extends Entity {

  #[Range(1, null)]
  #[Primary]
  private int $customer_id;

  private CustomerAuthTypeEnum $authType;

//  #[NotEmpty]
  #[Length(min: 1, max: 150)]
  private string $authValue;
}
