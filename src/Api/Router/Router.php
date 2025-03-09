<?php
declare(strict_types=1);


namespace App\Api\Router;


use App\Api\Request\RequestInterface;
use App\Api\Response\ResponseInterface;
use App\Exception\ExceptionHandler;
use App\Http\Enum\MethodEnum;
use App\Middleware\MiddlewareInterface;


/**
 * Maska URL může obsahovat argumentu ve formátu {name:regex} nebo wildcard *
 */
class Router implements RouteDefinitionInterface {

  /**
   * @var Route[]
   */
  private array $routes = [];

  private ExceptionHandler $exceptionHandler;

  /**
   * @var array<int, array{middleware: MiddlewareInterface, filter: ?\Closure}>
   */
  private array $middlewares = [];

  /**
   * @var RequestInterface<array-key, mixed>
   */
  private RequestInterface $request;

  private RouteHandlerFactory $routeHandlerFactory;

  /**
   * @param RequestInterface<array-key, mixed> $Request
   */
  public function __construct(RequestInterface $Request, RouteHandlerFactory $RouteHandlerFactory, ExceptionHandler $ExceptionHandler) {
    $this->request = $Request;
    $this->routeHandlerFactory = $RouteHandlerFactory;
    $this->exceptionHandler = $ExceptionHandler;
  }

  public function addMiddleware(MiddlewareInterface $Middleware, ?\Closure $Filter = null): void {
    $this->middlewares[] = [
      "middleware" => $Middleware,
      "filter" => $Filter,
    ];
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
   * @param \Closure|array{0: class-string, 1: string} $handler
   */
  public function option(string $url, \Closure|array $handler): Route {
    return $this->createRoute(MethodEnum::OPTIONS, $url, $handler);
  }


  /**
   * @param class-string<\Throwable> $class
   */
  public function setErrorHandler(string $class, \Closure $Closure, ?\Closure $Filter = null): void {
    $this->exceptionHandler->addErrorHandler($class, $Closure, $Filter);
  }


  /**
   * @throws \App\Exception\ExceptionHandlerException
   */
  public function run(): ResponseInterface {
    $Request = $this->request;

    $FilterPayload = new RouteFilterPayload($Request);

    try {
      $Route = $this->findRouteByRequest($Request);
    }catch(\Exception $e) {
      $FilterPayload->exception = $e;
      return $this->catchError($FilterPayload);
    }

    if(!$Route) {
      $FilterPayload->exception = new RouterNotFoundException("No route found");
      return $this->catchError($FilterPayload);
    }

    $FilterPayload->route = $Route;

    try {
      $this->applyMiddlewares($Request, $FilterPayload);
      return $Route->run($Request);

    }catch(\Exception $e) {
      $FilterPayload->exception = $e;
      return $this->catchError($FilterPayload);
    }
  }


  /**
   * @param RequestInterface<array-key, mixed>|null $Request
   */
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
   * @param RequestInterface<array-key, mixed> $Request
   * @return void
   */
  private function applyMiddlewares(RequestInterface $Request, RouteFilterPayload $Payload): void {
    $next = function() {
    };

    foreach (array_reverse($this->middlewares) as ["middleware" => $Middleware, "filter" => $Filter]) {
      /** @var MiddlewareInterface $Middleware */
      /** @var ?\Closure $Filter */

      if($Payload->route AND !$Payload->route->isMiddlewareApplicable($Middleware)) {
        continue;
      }

      if(!$Filter OR $Filter($Payload)) {
        $current = $next;
        $next = function (RequestInterface $Request) use ($Middleware, $current): void {
          $Middleware->handle($Request, $current);
        };
      }
    }

    // Spuštění middleware řetězce
    $next($Request);
  }


  /**
   * @param \Closure|array{0: class-string, 1: string} $handler
   */
  private function createRoute(MethodEnum $Method, string $url, \Closure|array $handler): Route {
    $Route = new Route($Method, $url, $this->routeHandlerFactory->createHandler($handler));
    $this->addRoute($Route);

    return $Route;
  }


  /**
   * @param RouteFilterPayload $Payload
   * @return ResponseInterface
   * @throws RouterException
   * @throws \App\Exception\ExceptionHandlerException
   */
  private function catchError(RouteFilterPayload $Payload): ResponseInterface {
    $Exception = $Payload->exception;
    if(!$Exception) {
      throw new RouterException($Payload::class." does not have a exception. This is APP error.");
    }

    return $this->exceptionHandler->handle($Exception, [$Payload], [$Payload->request, $Exception]);
  }

}