<?php
declare(strict_types=1);

namespace App\Entity\Attribute\Value;

use App\Entity\EntityInterface;


/**
 * @implements ValueInterface<null>
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class DefaultValue implements ValueInterface {

  const NOW = "now";

  const CURDATE = "curdate";

  /**
   * @var self::*
   */
  private string $type;


  /**
   * @param self::* $type
   */
  public function __construct(string $type) {
    $this->type = $type;
  }


  /**
   * @param mixed $oldValue
   * @return mixed
   */
  public function generate(mixed $oldValue, ?EntityInterface $Entity = null): mixed {
    return match($this->type) {
      self::NOW => new \DateTime(),
      self::CURDATE => (new \DateTime())->setTime(0, 0, 0)
    };
  }

}