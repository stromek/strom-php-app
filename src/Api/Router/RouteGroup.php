<?php
declare(strict_types=1);


namespace App\Api\Router;


use App\Api\Request\RequestInterface;
use App\Api\Response\ResponseInterface;
use App\Http\Enum\MethodEnum;


class RouteGroup {

  private string $url;

  private Router $router;


  public function __construct(Router $Router, string $url, \Closure $handler) {
    $this->url = $url;
    $this->router = $Router;

    $handler($this);
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


  private function makeURL(string $url): string {
    // Kontrola aby URL nebyla "/prefix/" . "/url.." (/prefix//url)
    if(str_ends_with($this->url, "/") AND str_starts_with($url, "/")) {
      return $this->url.substr($url, 1);
    }

    return $this->url.$url;
  }

}