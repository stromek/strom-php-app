<?php
declare(strict_types=1);
namespace Util;

use App\Util\Arr;
use PHPUnit\Framework\TestCase;


class ArrTest extends TestCase {

  public function testCherryPickKey() {
    $arr = ["color" => "blue", "size" => 5];

    $this->assertEquals(["color" => "blue"], Arr::create($arr)->cherryPickKey(["color"])->toArray());
  }

  public function testFilterAssocValue() {
    $array = [
      ["group" => 1, "name" => "name1"],
      ["group" => 2, "name" => "name2"],
      ["group" => 2, "name" => "name3"],
      ["group" => 3, "name" => "name4"],
    ];

    $result = Arr::create($array)->filterAssocValue("group", 2)->toArray();
    $this->assertEquals([1 => $array[1], 2 => $array[2]], $result, "Fixed value");


    $result = Arr::create($array)->filterAssocValue("name", function(string $name): bool {
      return in_array($name, ["name1", "name4"]);
    })->toArray();

    $this->assertEquals([0 => $array[0], 3 => $array[3]], $result, "Function");
  }

}
