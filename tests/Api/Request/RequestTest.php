<?php
declare(strict_types=1);
namespace Api\Request;

use App\Api\Request\Request;
use App\Http\Enum\MethodEnum;
use PHPUnit\Framework\TestCase;


class RequestTest extends TestCase {

  public function testGetUri(): void {
    $uri = "http://localhost/test";
    $HttpRequest = $this->createHttpRequest($uri);

    $Request = new Request($HttpRequest);
    $this->assertEquals($uri, $Request->getUri());
  }


  public function testMethod(): void {
    $Method = MethodEnum::POST;
    $HttpRequest = $this->createHttpRequest(Method: $Method);

    $Request = new Request($HttpRequest);
    $this->assertEquals($Method, $Request->getMethod());
    $this->assertFalse(MethodEnum::GET === $Request->getMethod());
  }


  public function testGetHeaderLine(): void {
    $contentType = "phpunit/text";
    $HttpRequest = $this->createHttpRequest(contentType: $contentType);

    $Request = new Request($HttpRequest);
    $this->assertEquals($contentType, $Request->getHeaderLine("Content-type"));
  }


  public function testGetQuery(): void {

    $data = [
      "format" => "xml",
      "id" => 1,
      "arr" => ["a", "b"]
    ];
    $query = http_build_query($data);

    $uri = "http://localhost/?".$query;
    $HttpRequest = $this->createHttpRequest($uri);

    $Request = new Request($HttpRequest);
    $this->assertEquals($data['format'], $Request->getQuery("format"));
    $this->assertEquals($data['arr'], $Request->getQuery("arr"));
    $this->assertEquals($data['id'], $Request->getQuery("id"));
    $this->assertNull($Request->getQuery("nonExists"));
  }


  /**
   * @param string $uri
   * @return \GuzzleHttp\Psr7\Request
   */
  private function createHttpRequest(string $uri = "http://localhost/", MethodEnum $Method = MethodEnum::GET, string $contentType = 'application/json'): \GuzzleHttp\Psr7\Request {
    return new \GuzzleHttp\Psr7\Request(
      $Method->value,
      $uri,
      ['Content-Type' => $contentType]
    );
  }


}
