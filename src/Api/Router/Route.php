<?php
declare(strict_types=1);


namespace App\Api\Router;


use App\Api\Request\RequestInterface;
use App\Api\Response\ResponseInterface;
use App\Http\Enum\MethodEnum;
use App\Middleware\MiddlewareInterface;
use App\Util\RouterUtil;


class Route {

  private MethodEnum $method;

  private string $url;

  private RouteHandler $handler;

  private bool|\Closure $middlewareFilter = true;

  public function __construct(MethodEnum $Method, string $url, RouteHandler $RouteHandler) {
    $this->method = $Method;
    $this->url = $url;
    $this->handler = $RouteHandler;
  }


  /**
   * Filtrování middleware
   *
   *  true = platí vše
   *  false = neplatí nic
   *  \Closure(MiddlewareInterface $Middleware, Route $Route): bool = metoda pro filtrování
   *
   * @param bool|\Closure $filter
   */
  public function setMiddlewareFilter(bool|\Closure $filter): void {
    $this->middlewareFilter = $filter;
  }


  /**
   * Zda middleware je aplikovatelný na tuto routu
   */
  public function isMiddlewareApplicable(MiddlewareInterface $Middleware): bool {
    $filterFunction = $this->middlewareFilter;

    return is_bool($filterFunction) ? $filterFunction : $filterFunction($Middleware, $this);
  }


  public function isRequestMatch(RequestInterface $Request): bool {
    return $this->isMatch($Request->getMethod(), $Request->getUri()->getPath());
  }


  public function isMatch(MethodEnum $Method, string $path): bool {
    return $this->isMethodMatch($Method) AND $this->isPathMatch($path);
  }


  public function isMethodMatch(MethodEnum $Method): bool {
    return $Method == $this->method;
  }


  public function isPathMatch(string $path): bool {
    return preg_match($this->createRegex(), $path) === 1;
  }


  public function run(RequestInterface $Request): ResponseInterface {
    return $this->handler->__invoke($this->parseArguments($Request));
  }


  /**
   * @param RequestInterface $Request
   * @return array<string, string>
   */
  private function parseArguments(RequestInterface $Request): array {
    return RouterUtil::parsePathArgumentsForULRMask($this->url, $Request->getUri()->getPath());
  }

  private function createRegex(): string {
    return RouterUtil::createRegexFromUrlMask($this->url);
  }

}