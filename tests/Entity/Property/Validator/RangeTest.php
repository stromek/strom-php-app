<?php
declare(strict_types=1);
namespace Entity\Property\Validator;

use App\Entity\Attribute\Validator\Range;
use PHPUnit\Framework\TestCase;


class RangeTest extends TestCase {

  public function testValidate(): void {

    $Object = new Range(1, 100);
    $this->assertTrue($Object->isValid(1));
    $this->assertTrue($Object->isValid(100));
  }


  public function testValidateNoEdge(): void {
    $Object = new Range(1, null);
    $this->assertTrue($Object->isValid(1));
    $this->assertTrue($Object->isValid(100));

    $Object = new Range(null, 10);
    $this->assertTrue($Object->isValid(1));
    $this->assertTrue($Object->isValid(-10));
  }


  public function testValidateFail(): void {
    $Object = new Range(20, 30);
    $this->assertFalse($Object->isValid(19));
    $this->assertFalse($Object->isValid(31));

    $Object = new Range(20, null);
    $this->assertFalse($Object->isValid(19));
  }

}
