<?php
declare(strict_types=1);
namespace Entity\Property\Mutator;

use App\Entity\Attribute\Mutator\Decimal;
use PHPUnit\Framework\TestCase;


class DecimalTest extends TestCase {


  public function testDecimalMutator(): void {
    $value = 10 / 3;
    $decimalPart = 2;
    $mode = PHP_ROUND_HALF_UP;

    $Decimal = new Decimal($decimalPart, $mode);
    $this->assertEquals(round($value, $decimalPart, $mode), $Decimal->mutate($value));
    $this->assertEquals(3.33, $Decimal->mutate($value));
  }
}
