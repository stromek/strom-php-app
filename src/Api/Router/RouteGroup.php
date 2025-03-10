<?php
declare(strict_types=1);


namespace App\Api\Router;


use App\Api\Request\RequestInterface;
use App\Api\Response\ResponseInterface;
use App\Http\Enum\MethodEnum;
use App\Middleware\MiddlewareInterface;


class RouteGroup implements RouteDefinitionInterface {

  private string $url;

  private Router $router;


  public function __construct(Router $Router, string $url, \Closure $handler) {
    $this->url = $url;
    $this->router = $Router;

    $handler($this);
  }


  /**
   * Odchyceni chybi v rámci RouteGroup
   *
   * @param class-string<\Throwable> $class
   * @return void
   */
  public function setErrorHandler(string $class, \Closure $Closure): void {
    $this->router->setErrorHandler($class, $Closure, $this->filterRequestHandler(...));
  }

  public function addMiddleware(MiddlewareInterface $Middleware, ?\Closure $Filter = null): void {
    $this->router->addMiddleware($Middleware, $this->filterRequestHandler(...));
  }


  public function group(string $url, \Closure $handler): RouteGroup {
    return new RouteGroup($this->router, $this->makeURL($url), $handler);
  }


  /**
   * @param \Closure|array{0: class-string, 1: string} $handler
   */
  public function get(string $url, \Closure|array $handler): Route {
    return $this->router->get($this->makeURL($url), $handler);
  }


  /**
   * @param \Closure|array{0: class-string, 1: string} $handler
   */
  public function post(string $url, \Closure|array $handler): Route {
    return $this->router->post($this->makeURL($url), $handler);
  }


  /**
   * @param \Closure|array{0: class-string, 1: string} $handler
   */
  public function put(string $url, \Closure|array $handler): Route {
    return $this->router->put($this->makeURL($url), $handler);
  }


  /**
   * @param \Closure|array{0: class-string, 1: string} $handler
   */
  public function patch(string $url, \Closure|array $handler): Route {
    return $this->router->patch($this->makeURL($url), $handler);
  }


  /**
   * @param \Closure|array{0: class-string, 1: string} $handler
   */
  public function delete(string $url, \Closure|array $handler): Route {
    return $this->router->delete($this->makeURL($url), $handler);
  }


  /**
   * @param \Closure|array{0: class-string, 1: string} $handler
   */
  public function option(string $url, \Closure|array $handler): Route {
    return $this->router->option($this->makeURL($url), $handler);
  }


  private function makeURL(string $url): string {
    // Kontrola aby URL nebyla "/prefix/" . "/url.." (/prefix//url)
    if(str_ends_with($this->url, "/") AND str_starts_with($url, "/")) {
      return $this->url.substr($url, 1);
    }

    return $this->url.$url;
  }


  /**
   * Filtrovací funkce pro zachycení pouze požadavků které splňují URL prefix
   * 
   * @return bool
   */
  private function filterRequestHandler(RouteFilterPayload $Payload): bool {
    $Request = $Payload->request;
    if(!$Request) {
      return false;
    }

    return str_starts_with($Request->getUri()->getPath(), $this->url);
  }

}