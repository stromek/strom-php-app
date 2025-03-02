<?php
declare(strict_types=1);


use Tracy\Debugger;
use DI\Container;
use App\Env\AppEnv;
use App\Api\Request\Request;
use App\Api\Response\ResponseFactory;
use App\Api\Response\ResponseInterface;
use App\Api\Router\RouteDefinitionInterface;
use App\Controller\App\AppController;
use App\Exception\AppException;


return function (Container $Container, RouteDefinitionInterface $Router): void {
  $Router->get("/", [AppController::class, "index"]);
  $Router->get("/*", [AppController::class, "error404"]);


  $Router->setErrorHandler(\Exception::class, function(Request $Request, \Exception $Exception) use ($Container): ResponseInterface {
    if(\App\Env\AppEnv::displayInternalError()) {
      Debugger::getBlueScreen()->render($Exception);
      exit(1);
    }

    Debugger::tryLog($Exception, Debugger::EXCEPTION);

    return $Container->get(ResponseFactory::class)->createResponseFromException(
      new AppException("Internal error.", 0, $Exception)
    );
  });

};

