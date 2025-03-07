<?php
declare(strict_types=1);
namespace Api\Router;

use App\Api\Router\Route;
use App\Api\Router\RouteHandler;
use App\Http\Enum\MethodEnum;
use App\Middleware\MiddlewareInterface;
use PHPUnit\Framework\TestCase;


class RouteTest extends TestCase {

  public function testMiddlewareApplicableNoOne(): void {
    $Route = $this->createRoute("*");
    $Route->setMiddlewareFilter(false);

    $this->assertFalse($Route->isMiddlewareApplicable($this->createMiddleWare()));
    $this->assertFalse($Route->isMiddlewareApplicable($this->createMiddleWare()));
  }

  public function testMiddlewareApplicableAll(): void {
    $Route = $this->createRoute("*");
    $Route->setMiddlewareFilter(true);

    $this->assertTrue($Route->isMiddlewareApplicable($this->createMiddleWare()));
    $this->assertTrue($Route->isMiddlewareApplicable($this->createMiddleWare()));
  }

  public function testMiddlewareApplicableFilter(): void {
    $Route = $this->createRoute("*");

    $allow = [$this->createMiddleWare(), $this->createMiddleWare()];
    $deny = [$this->createMiddleWare(), $this->createMiddleWare()];

    $Route->setMiddlewareFilter(function($Middleware) use($allow): bool {
      return !is_null(array_find($allow, fn($M) => $Middleware->id === $M->id));
    });

    array_map(fn($M) => $this->assertTrue($Route->isMiddlewareApplicable($M)), $allow);
    array_map(fn($M) => $this->assertFalse($Route->isMiddlewareApplicable($M)), $deny);
  }


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

  private function createMiddleWare(): MiddlewareInterface {
    $id = uniqid();

    return new class($id) implements MiddlewareInterface {
      public function __construct(public readonly string $id) {}

      public function handle(\App\Api\Request\RequestInterface $Request, callable $next): void {}
    };
  }

  private function createRouteHandler(): \App\Api\Router\RouteHandler {
    return new \App\Api\Router\RouteHandler(function() {
    });
  }
}
