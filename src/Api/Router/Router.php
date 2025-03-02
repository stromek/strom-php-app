<?php
declare(strict_types=1);


namespace App\Api\Router;


use App\Api\Request\Request;
use App\Api\Request\RequestInterface;
use App\Api\Response\ResponseInterface;
use App\Http\Enum\MethodEnum;
use Tracy\Debugger;


/**
 * Maska URL může obsahovat argumentu ve formátu {name:regex} nebo wildcard *
 */
class Router implements RouteDefinitionInterface {

  /**
   * @var Route[]
   */
  private array $routes = [];

  /**
   * @var array<class-string<\Exception>, array<int, array{handler: \Closure, filter: ?\Closure}>>
   */
  private array $errorHandlers = [];

  private RequestInterface $request;

  private RouteHandlerFactory $routeHandlerFactory;


  public function __construct(RequestInterface $Request, RouteHandlerFactory $RouteHandlerFactory) {
    $this->request = $Request;
    $this->routeHandlerFactory = $RouteHandlerFactory;
  }


  public function group(string $url, \Closure $handler): RouteGroup {
    return new RouteGroup($this, $url, $handler);
  }


  /**
   * @param \Closure|array{0: class-string, 1: string} $handler
   */
  public function get(string $url, \Closure|array $handler): Route {
    return $this->createRoute(MethodEnum::GET, $url, $handler);
  }


  /**
   * @param \Closure|array{0: class-string, 1: string} $handler
   */
  public function post(string $url, \Closure|array $handler): Route {
    return $this->createRoute(MethodEnum::POST, $url, $handler);
  }


  /**
   * @param \Closure|array{0: class-string, 1: string} $handler
   */
  public function put(string $url, \Closure|array $handler): Route {
    return $this->createRoute(MethodEnum::PUT, $url, $handler);
  }


  /**
   * @param \Closure|array{0: class-string, 1: string} $handler
   */
  public function patch(string $url, \Closure|array $handler): Route {
    return $this->createRoute(MethodEnum::PATCH, $url, $handler);
  }


  /**
   * @param \Closure|array{0: class-string, 1: string} $handler
   */
  public function delete(string $url, \Closure|array $handler): Route {
    return $this->createRoute(MethodEnum::DELETE, $url, $handler);
  }


  /**
   * @param class-string<\Exception> $class
   */
  public function setErrorHandler(string $class, \Closure $Closure, ?\Closure $Filter = null): void {
    $this->errorHandlers[$class] ??= [];
    $this->errorHandlers[$class][] = [
      "handler" => $Closure,
      "filter" => $Filter,
    ];
  }


  /**
   * @throws RouterUncaughtExceptionException
   */
  public function run(): ResponseInterface {
    $Request = $this->request;

    try {
      $Route = $this->findRouteByRequest($Request);
    }catch(\Exception $e) {
      return $this->catchError($e, ["request" => $Request]);
    }

    if(!$Route) {
      return $this->catchError(new RouterNotFoundException("No route found"), ["request" => $Request]);
    }

    try {
      return $Route->run($Request);
    }catch(\Exception $e) {
      return $this->catchError($e, ["request" => $Request, "route" => $Route]);
    }
  }


  public function findRouteByRequest(?RequestInterface $Request = null): ?Route {
    $Request ??= $this->request;

    foreach($this->routes as $Route) {
      if($Route->isRequestMatch($Request)) {
        return $Route;
      }
    }

    return null;
  }


  public function addRoute(Route $Route): void {
    $this->routes[] = $Route;
  }


  /**
   * @param \Throwable $Exception
   * @param array<array-key, mixed> $options
   * @return ResponseInterface
   * @throws RouterUncaughtExceptionException
   */
  private function catchError(\Throwable $Exception, array $options = []): ResponseInterface {
    foreach($this->errorHandlers as $class => $handlers) {
      // Neni shoda třidy vyjímky
      if($class !== $Exception::class AND !is_subclass_of($Exception::class, $class)) {
        continue;
      }

      foreach($handlers as ["handler" => $handler, "filter" => $filter]) {
        if(!$filter OR $filter($Exception, $options)) {
          return $handler($this->request, $Exception);
        }
      }
    }

    throw new RouterUncaughtExceptionException("Uncaught exception '".$Exception::class."' (".$Exception->getMessage()."). Use ".self::class."::setErrorHandler() to catch exception.", 0, $Exception);
  }


  /**
   * @param \Closure|array{0: class-string, 1: string} $handler
   */
  private function createRoute(MethodEnum $Method, string $url, \Closure|array $handler): Route {
    $Route = new Route($Method, $url, $this->routeHandlerFactory->createHandler($handler));
    $this->addRoute($Route);

    return $Route;
  }

}