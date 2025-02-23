<?php
declare(strict_types=1);

namespace App\Entity\Property;


class PropertyException extends \App\Exception\EntityException {

  const NOT_INITIALIZED = 1;

  const NOT_EXISTS = 2;

  const NO_DEFAULT_VALUE = 3;

  const VALIDATOR_FAILED = 4;

}