<?php
declare(strict_types=1);
namespace Entity\Property\Validator;

use App\Entity\Attribute\Validator\NotEmpty;
use PHPUnit\Framework\TestCase;


class NotEmptyTest extends TestCase {

  public function testValidate(): void {

    $Object = new NotEmpty();
    $this->assertTrue($Object->isValid("string"));
    $this->assertFalse($Object->isValid(""));
  }
}
