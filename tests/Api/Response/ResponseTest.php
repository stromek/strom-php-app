<?php
declare(strict_types=1);
namespace Api\Response;

use App\Api\Response\Response;
use PHPUnit\Framework\TestCase;


class ResponseTest extends TestCase {

  public function testHeaders(): void {
    $Response = new Response(\App\Http\Enum\StatusCodeEnum::STATUS_OK, "", [
      "x-header" => "x-value"
    ]);

    $this->assertTrue($Response->hasHeader("x-header"));
    $this->assertFalse($Response->hasHeader("no-header"));
    $this->assertEquals("x-value", $Response->getHeader("x-header"));
  }
}
