<?php
declare(strict_types=1);
namespace Entity\Property;

use App\Entity\Attribute\Mutator\MutatorInterface;
use App\Entity\Property\Property;
use App\Entity\Property\PropertyException;
use App\Tests\Mock\Entity\EntityMock;
use PHPUnit\Framework\TestCase;


class PropertyTest extends TestCase {

  private EntityMock $entity;

  protected function setUp(): void {
    $this->entity = new EntityMock();
  }


  public function testGetName(): void {
    $Property = new Property($this->entity, "id");
    $this->assertEquals("id", $Property->getName());
  }


  public function testGetValue(): void {
    $Property = new Property($this->entity, "id");
    $this->entity->id = 20;
    $this->assertEquals(20, $Property->getValue());
    $this->assertEquals(20, $Property->getValueSafe());
  }

  public function testHasAttribute(): void {
    $this->assertCount(0, (new Property($this->entity, "blank"))->getAttributes());
    $this->assertCount(2, (new Property($this->entity, "id"))->getAttributes());
  }

  public function testHasAttributeFilter(): void {
    $this->assertCount(1, (new Property($this->entity, "decimal"))->getAttributes(MutatorInterface::class));
    $this->assertCount(0, (new Property($this->entity, "id"))->getAttributes(MutatorInterface::class));
  }


  public function testMutator(): void {
    $Property = new Property($this->entity, "decimal");
    $this->entity->decimal = 10/3;

    $this->assertEquals(3.33, $this->entity->decimal);
  }

  public function testMultipleMutator(): void {
    $Property = new Property($this->entity, "dividend");
    $this->entity->dividend = 100;

    $this->assertEquals(round(100 / 3) / 2, $this->entity->dividend);
  }

  public function testValidatorFailed(): void {
    $this->expectException(PropertyException::class);
    $this->expectExceptionCode(PropertyException::VALIDATOR_FAILED);
    $Property = new Property($this->entity, "id");
    $this->entity->id = 0;
  }


  public function testMissingPropertyInDocs(): void {
    $this->expectException(PropertyException::class);
    $Property = new Property($this->entity, "_noproperty");
  }


  public function testNotHasDefaultValue(): void {
    $PropertyID = new Property($this->entity, "id");
    $this->assertFalse($PropertyID->hasDefaultValue(false));
  }


  public function testHasDefaultValue(): void {
    $PropertyID = new Property($this->entity, "code");
    $this->assertTrue($PropertyID->hasDefaultValue(false));
  }


  public function testGetDefaultValue(): void {
    $Property = new Property($this->entity, "code");

    $this->assertEquals(10, $Property->getDefaultValue());
    // I po změně je stále 10
    $this->entity->code = 20;
    $this->assertEquals(10, $Property->getDefaultValue());
  }

  public function testDefaultValueGenerator(): void {
    $this->assertEquals(date("Y-m-d"), (new Property($this->entity, "date"))->getValue()->format("Y-m-d"));
    $this->assertEquals(date("Y-m-d"), (new Property($this->entity, "datetime"))->getValue()->format("Y-m-d"));
  }

}
