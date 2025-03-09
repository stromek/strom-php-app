<?php
declare(strict_types=1);
namespace App\Exception;

use \PHPUnit\Framework\TestCase;


class ExceptionHandlerTest extends TestCase {


  public function testAddErrorHandler(): void {
    $handler = new ExceptionHandler();

    $handler->addErrorHandler(\RuntimeException::class, function () {
      return "Handled RuntimeException";
    });

    $this->assertTrue($handler->hasErrorHandler(\RuntimeException::class));
    $this->assertFalse($handler->hasErrorHandler(\Exception::class));
  }


  public function testHandleWithMatchingHandler(): void {
    $handler = new ExceptionHandler();

    $handler->addErrorHandler(\RuntimeException::class, function () {
      return "Handled RuntimeException";
    });

    $result = $handler->handle(new \RuntimeException("Test exception"));

    $this->assertSame("Handled RuntimeException", $result);
  }


  public function testHandleWithFilter(): void {
    $Handler = new ExceptionHandler();

    $Handler->addErrorHandler(\RuntimeException::class,
      function () {
        return "Filtered and handled";
      },
      function ($arg) {
        return $arg === true;
      }
    );

    $result = $Handler->handle(new \RuntimeException("Test exception"), [true]);
    $this->assertSame("Filtered and handled", $result);

    $this->expectException(ExceptionHandlerException::class);
    $Handler->handle(new \RuntimeException("Test exception"), [false]);
  }


  public function testHandleWithoutHandler(): void {
    $Handler = new ExceptionHandler();

    $this->expectException(ExceptionHandlerException::class);
    $Handler->handle(new \RuntimeException("Test exception"));
  }


  public function testHandleWithSubclass(): void {
    $Handler = new ExceptionHandler();

    $Handler->addErrorHandler(\Exception::class, function () {
      return "Handled Exception";
    });

    $result = $Handler->handle(new \RuntimeException("Test exception"));

    $this->assertSame("Handled Exception", $result);
  }
}