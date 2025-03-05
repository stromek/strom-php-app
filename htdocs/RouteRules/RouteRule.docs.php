<?php
declare(strict_types=1);


use App\Api\Router\RouteDefinitionInterface;
use App\Controller\Docs\DocsController;
use DI\Container;


return function (Container $Container, RouteDefinitionInterface $Route): void {
  $Route->get("/", [DocsController::class, "index"]);
  $Route->get("/swagger.json", [\App\Controller\Docs\DocsController::class, "swagger"]);
};