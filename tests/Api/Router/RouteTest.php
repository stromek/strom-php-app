<?php
declare(strict_types=1);
namespace Api\Router;

use App\Api\Router\Route;
use App\Http\Enum\MethodEnum;
use PHPUnit\Framework\TestCase;


class RouteTest extends TestCase {


  public function testMethodMatch(): void {
    $Route = $this->createRoute("*");
    $this->assertTrue($Route->isMethodMatch(MethodEnum::GET));
    $this->assertFalse($Route->isMethodMatch(MethodEnum::POST));

    $Route = $this->createRoute("*", MethodEnum::POST);
    $this->assertTrue($Route->isMethodMatch(MethodEnum::POST));
    $this->assertFalse($Route->isMethodMatch(MethodEnum::GET));
  }


  public function testPathMatchEqual(): void {
    $Route = $this->createRoute("/help/");
    $this->assertTrue($Route->isPathMatch("/help/"));

    $this->assertFalse($Route->isPathMatch("/help"));
    $this->assertFalse($Route->isPathMatch("help"));
    $this->assertFalse($Route->isPathMatch("/help/none/"));
  }


  public function testPathMatchWildcard(): void {
    $Route = $this->createRoute("/test/*");
    $this->assertTrue($Route->isPathMatch("/test/"));
    $this->assertTrue($Route->isPathMatch("/test/test"));
    $this->assertFalse($Route->isPathMatch("/test"));

    $Route = $this->createRoute("/begin/*/end");
    $this->assertTrue($Route->isPathMatch("/begin/middle/end"));
  }


  public function testPathMatchRegex(): void {
    $Route = $this->createRoute("/order/{id:[0-9]+}/");
    $this->assertTrue($Route->isPathMatch("/order/1/"));
    $this->assertTrue($Route->isPathMatch("/order/00/"));

    $this->assertFalse($Route->isPathMatch("/order/1"));
    $this->assertFalse($Route->isPathMatch("/order/1/1"));
    $this->assertFalse($Route->isPathMatch("/order/abc/"));
    $this->assertFalse($Route->isPathMatch("/order/none/"));
  }


  private function createRoute(string $url, MethodEnum $Method = MethodEnum::GET, \App\Api\Router\RouteHandler $RouteHandler = null): Route {
    $RouteHandler ??= $this->createRouteHandler();

    return new Route($Method, $url, $RouteHandler);
  }

  private function createRouteHandler(): \App\Api\Router\RouteHandler {
    return new \App\Api\Router\RouteHandler(function() {
    });
  }
}
