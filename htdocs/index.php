<?php
declare(strict_types=1);

use App\Api\Request\Request;
use App\Api\Response\ResponseInterface;
use App\Api\Router\RouteDefinitionInterface;
use App\Api\Router\RouteGroup;
use App\Api\Router\Router;


require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../boot/bootstrap.php';


$Container = \App\Factory\ContainerFactory::create();

/** @var Router $Router */
$Router = $Container->get(Router::class);


$loadRouteRule = function(string $filename, ?RouteDefinitionInterface $RouteDefinition = null) use ($Container, $Router): void {
  $filepath = __DIR__.DIRECTORY_SEPARATOR."RouteRules".DIRECTORY_SEPARATOR.$filename;
  if(!file_exists($filepath)) {
    throw new RuntimeException("RouteRules file '{$filepath}' not exist");
  }

  (require ($filepath))($Container, $RouteDefinition ?? $Router);
};


// API V1
$Router->group("/api/v1/", function(RouteGroup $RouteGroup) use ($loadRouteRule){
  $loadRouteRule("RouteRule.api.1.php", $RouteGroup);
});

// Zakladni APP
$loadRouteRule("RouteRule.app.php");


$Router->run()->send();