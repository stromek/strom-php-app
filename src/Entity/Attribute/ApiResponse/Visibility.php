<?php
declare(strict_types=1);

namespace App\Entity\Attribute\ApiResponse;


#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Visibility implements ApiResponseInterface {

  const VISIBLE = "visible";

  const HIDDEN = "hidden";

  protected ?string $fromVersion;

  protected ?string $toVersion;

  private string $visibility;


  /**
   * @param self::VISIBLE|self::HIDDEN $visibility
   */
  public function __construct(string $visibility = self::VISIBLE, ?string $fromVersion = null, ?string $toVersion = null) {
    $this->visibility = $visibility;
    $this->fromVersion = $fromVersion;
    $this->toVersion = $toVersion;
  }


  public function isVisible(?string $version = null): bool {
    return $this->visibility === self::VISIBLE AND $this->isActive($version);
  }


  public function isHidden(?string $version = null): bool {
    return $this->visibility === self::HIDDEN AND $this->isActive($version);
  }


  private function isActive(?string $version = null): bool {
    if(is_null($version)) {
      return true;
    }

    if(is_null($this->fromVersion) AND is_null($this->toVersion)) {
      return true;
    }

    if(!is_null($this->fromVersion) AND version_compare($this->fromVersion, $version, '<')) {
      return false;
    }

    if(!is_null($this->toVersion) AND version_compare($this->toVersion, $version, '>')) {
      return false;
    }

    return true;
  }


}