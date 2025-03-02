<?php
declare(strict_types=1);


use Tracy\Debugger;
use DI\Container;
use App\Api\Request\Request;
use App\Api\Response\ResponseFactory;
use App\Api\Response\ResponseInterface;
use App\Api\Router\RouteDefinitionInterface;
use App\Controller\Api\ApiController;
use App\Controller\Customer\CustomerController;
use App\Exception\AppException;


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


  $Route->get("/", [ApiController::class, "index"]);

  // Zákazník
  $Route->group("/customer/", function (RouteDefinitionInterface $Route): void {
    $Route->get("{id:[0-9]+}/", [CustomerController::class, "detail"]);
    $Route->get("{id:[0-9]+}/users/", [CustomerController::class, "listOfUsers"]);

    $Route->get("{customer_id:[0-9]+}/user/{id:[0-9]+}/", [CustomerController::class, "userDetail"]);
  });

  $Route->get("/*", [ApiController::class, "error404"]);
};