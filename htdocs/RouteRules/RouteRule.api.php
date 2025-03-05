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
use DI\Container;
use Tracy\Debugger;


return function (Container $Container, RouteDefinitionInterface $Route): void {
  $Route->setErrorHandler(\Exception::class, function(Request $Request, \Exception $Exception) use ($Container): ResponseInterface {
    $payload = null;

    if(\App\Env\AppEnv::displayInternalError()) {
      $payload = ["_exception" => \App\Util\ExceptionDump::toArray($Exception)];
    }else {
      Debugger::tryLog($Exception, Debugger::EXCEPTION);
    }

    return $Container->get(ResponseFactory::class)->createApiResponseFromException(
      new AppException("Internal error.", 0, $Exception), null, $payload
    );
  });



  /**
   * Autentifikace zkaznika
   */
  $AuthenticationCustomerMiddleware = $Container->get(\App\Middleware\AuthenticationCustomerMiddleware::class);
  $Route->addMiddleware($AuthenticationCustomerMiddleware);


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
    $Route->get("{hash:[a-zA-Z0-9]+}/", [ThreadController::class, "detailByHash"]);
    $Route->get("{hash:[a-zA-Z0-9]+}/messages/", [ThreadController::class, "listOfMessages"]);

//    $Route->get("{id:[0-9]+}/users/", [CustomerController::class, "listOfUsers"]);
//    $Route->get("{customer_id:[0-9]+}/user/{id:[0-9]+}/", [CustomerController::class, "userDetail"]);
  });

  $Route->get("/*", [ApiController::class, "error404"]);
};