<?php
declare(strict_types=1);


namespace App\Api\Router;


use App\Api\Request\Request;
use App\Api\Request\RequestInterface;
use App\Api\Response\ResponseInterface;
use App\Http\Enum\MethodEnum;


/**
 * Maska URL může obsahovat argumentu ve formátu {name:regex} nebo wildcard *
 */
class Router {


  /**
   * @var Route[]
   */
  private array $routes = [];

  /**
   * @var array<class-string<\Exception>, \Closure>
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
  public function setErrorHandler(string $class, \Closure $Closure): void {
    $this->errorHandlers[$class] = $Closure;
  }


  /**
   * @throws RouterUncaughtExceptionException
   */
  public function run(): ResponseInterface {
    try {
      $Route = $this->findRouteByRequest($this->request);
    }catch(\Exception $e) {
      return $this->catchError($e);
    }

    if(!$Route) {
      return $this->catchError(new RouterNotFoundException("No route found"));
    }

    try {
      return $Route->run($this->request);
    }catch(\Exception $e) {
      return $this->catchError($e);
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
   * @throws RouterUncaughtExceptionException
   */
  private function catchError(\Throwable $Exception): ResponseInterface {
    foreach($this->errorHandlers as $class => $errorHandler) {
      if (is_subclass_of($Exception::class, $class) OR $class === $Exception::class) {
        return $errorHandler($this->request, $Exception);
      }
    }

    throw new RouterUncaughtExceptionException("Uncaught exception '".get_class($Exception)."'. Use ".self::class."::setErrorHandler() to catch exception.", 0, $Exception);
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