<?php
declare(strict_types=1);

use App\Api\Router\Router;
use App\Env\AppEnv;
use Tracy\Debugger;


include __DIR__.'/../vendor/autoload.php';
include __DIR__.'/../boot/bootstrap.php';

$Container = \App\Factory\ContainerFactory::create();


/** @var Router $Router */
$Router = $Container->get(Router::class);

/**
 * Chyba pro repository
 */
$Router->setErrorHandler(\App\Repository\RepositoryException::class, function(\App\Api\Request\Request $Request, \Exception $Exception) use ($Container): \App\Api\Response\ResponseInterface {
  return $Container->get(\App\Api\Response\ResponseFactory::class)->createFromException($Exception);
});

/**
 * @TODO autorizace a autentifikace, rozlišení scope...
 */

/**
 * Routa nenalezena, řeší routa "/*"
 */
//$Router->setErrorHandler(\App\Api\Router\RouterNotFoundException::class, function(\App\Api\Request\Request $Request, \Exception $Exception) use ($Container): \App\Api\Response\ResponseInterface {
//  return $Container->get(\App\Api\Response\ResponseFactory::class)->createFromException($Exception);
//});

/**
 * Všechny ostatní chyby
 */
$Router->setErrorHandler(\Exception::class, function(\App\Api\Request\Request $Request, \Exception $Exception) use ($Container): \App\Api\Response\ResponseInterface {
  if(AppEnv::displayInternalError()) {
    Debugger::getBlueScreen()->render($Exception);
    exit(1);
  }

  Debugger::tryLog($Exception, Debugger::EXCEPTION);
  return $Container->get(\App\Api\Response\ResponseFactory::class)->createFromException(
    new \App\Exception\AppException("Internal error.", 0, $Exception)
  );
});


$Router->group("/api/", function(\App\Api\Router\RouteGroup $RouteGroup) {
  $RouteGroup->get("/", [\App\Controller\Api\ApiController::class, "index"]);

  $RouteGroup->get("/customer/{id:[0-9]+}/", [\App\Controller\Customer\CustomerController::class, "detail"]);
  $RouteGroup->get("/customer/{id:[0-9]+}/", [\App\Controller\Customer\CustomerController::class, "detail"]);
});

$Router->get("/", [\App\Controller\App\AppController::class, "index"]);
$Router->get("/*", [\App\Controller\App\AppController::class, "error404"]);

$Router->run()->send();