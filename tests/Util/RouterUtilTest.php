<?php
declare(strict_types=1);
namespace App\Util;

use PHPUnit\Framework\TestCase;


class RouterUtilTest extends TestCase {

  public function testParsePathArgumentsForULRMask(): void {
    $args = [
      "id" => 10,
      "key" => "key"
    ];

    $result = RouterUtil::parsePathArgumentsForULRMask("/path/{key:[a-z]+}/{id:[0-9]+}/", "/path/{$args['key']}/{$args['id']}/");
    $this->assertEquals($args['id'], $result['id']);
    $this->assertEquals($args['key'], $result['key']);
  }

}
