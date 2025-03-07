<?php
declare(strict_types=1);


use App\Api\Request\Request;
use App\Api\Response\ResponseFactory;
use App\Api\Response\ResponseInterface;
use App\Api\Router\RouteDefinitionInterface;
use App\Controller\Api\ApiController;
use App\Controller\Api\CustomerController;
use App\Controller\Api\ThreadController;
use App\Exception\AppException;
use App\Interface\AppErrorInterface;
use App\Middleware\AuthenticationCustomerMiddleware;
use App\Middleware\MiddlewareInterface;
use App\Util\RouterUtil;
use DI\Container;


return function (Container $Container, RouteDefinitionInterface $Route): void {
  $Route->setErrorHandler(AppErrorInterface::class, function(Request $Request, AppErrorInterface $Exception) use ($Container): ResponseInterface {
    return $Container->get(ResponseFactory::class)->createApiResponseFromException(
      $Exception, $Exception->getStatusCodeEnum(), RouterUtil::getErrorHandlerExceptionDetails($Exception)
    );
  });


  $Route->setErrorHandler(\Exception::class, function(Request $Request, \Exception $Exception) use ($Container): ResponseInterface {
    return $Container->get(ResponseFactory::class)->createApiResponseFromException(
      new AppException("Internal error.", 0, $Exception), null, RouterUtil::getErrorHandlerExceptionDetails($Exception)
    );
  });


  /**
   * Autentifikace zkaznika
   */
  $AuthenticationCustomerMiddleware = $Container->get(AuthenticationCustomerMiddleware::class);
  $Route->addMiddleware($AuthenticationCustomerMiddleware);


  /**
   * Preflight request a vypnuti autorizace pro tuto routu
   */
  $Route->option("/*", function() {
    return new \App\Api\Response\Response(\App\Http\Enum\StatusCodeEnum::STATUS_NO_CONTENT, "", [
      "Access-Control-Allow-Origin" => "*",
      "Access-Control-Allow-Methods" => "GET, POST",
      "Access-Control-Allow-Headers" => "Authorization, Content-Type, Correlation_id"
    ]);
  })->setMiddlewareFilter(fn(MiddlewareInterface $M) => $M::class !== $AuthenticationCustomerMiddleware::class);

  /**
   * Dokumentae
   */
  $Route->get("/swagger.json", [\App\Controller\Docs\DocsController::class, "swagger"]);

  /**
   * Zákazník
   */
  $Route->group("/customer/", function (RouteDefinitionInterface $Route): void {
    $Route->get("/", [CustomerController::class, "detail"]);
    $Route->get("/users/", [CustomerController::class, "listOfUsers"]);

    $Route->get("/user/{hash:[a-zA-Z0-9]+}/", [CustomerController::class, "userDetail"]);
  });


  /**
   * Thread
   */
  $Route->group("/thread/", function (RouteDefinitionInterface $Route): void {
    $Route->get("find/", [ThreadController::class, "detailByCode"]);
    $Route->get("{hash:[a-zA-Z0-9]+}/", [ThreadController::class, "detailByHash"]);
    $Route->get("{hash:[a-zA-Z0-9]+}/messages/", [ThreadController::class, "listOfMessages"]);

//    $Route->get("{id:[0-9]+}/users/", [CustomerController::class, "listOfUsers"]);
//    $Route->get("{customer_id:[0-9]+}/user/{id:[0-9]+}/", [CustomerController::class, "userDetail"]);
  });

  $Route->get("/*", [ApiController::class, "error404"]);
};