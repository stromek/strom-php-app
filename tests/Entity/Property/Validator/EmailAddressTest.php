<?php
declare(strict_types=1);
namespace Entity\Property\Validator;

use App\Entity\Attribute\Validator\EmailAddress;
use PHPUnit\Framework\TestCase;


class EmailAddressTest extends TestCase {

  public function testValidate(): void {
    $Object = new EmailAddress();

    $this->assertTrue($Object->isValid("test@example.com"), "Valid email address");
    $this->assertFalse($Object->isValid("test"), "Invalid email address");
  }

}
