<?php
declare(strict_types=1);
namespace Entity;

use App\Entity\Attribute\Mutator\Decimal;
use App\Entity\Property\PropertyException;
use App\Entity\Attribute\Validator\NotEmpty;
use PHPUnit\Framework\TestCase;


class EntityTest extends TestCase {

  public function testProperty(): void {
    $Entity = $this->createEntity();
    $Entity->id = 1;
    $this->assertEquals(1, $Entity->id);
  }


  public function testGetter(): void {
    $Entity = $this->createEntity();

    $Entity->getterModificationID = 10;
    $this->assertEquals(20, $Entity->getterModificationID);
  }


  public function testSetter(): void {
    $Entity = $this->createEntity();

    $Entity->setterModificationID = 10;
    $this->assertEquals(20, $Entity->setterModificationID);
  }


  public function testPropertyNotEmpty(): void {
    $Entity = $this->createEntity();

    $this->expectException(PropertyException::class);
    $Entity->name = "";
  }

  public function testPropertyMutator(): void {
    $Entity = $this->createEntity();
    $Entity->decimal = 1.1234;

    $this->assertEquals(1.12, $Entity->decimal);
  }


  private function createEntity(): object {
    /**
     * @property int $id
     * @property int $getterModificationID
     * @property int $setterModificationID
     * @property string $name
     * @property float $decimal
     */
    return new class extends \App\Entity\Entity {
      private int $id;

      private int $getterModificationID;

      private int $setterModificationID;

      #[NotEmpty]
      private string $name = "name";

      #[Decimal(2)]
      private float $decimal;

      public function getGetterModificationID(): int {
        return $this->getterModificationID * 2;
      }

      public function setSetterModificationID(int $setterModificationID): void {
        $this->setterModificationID = $setterModificationID * 2;
      }
    };
  }


}
