<?php
declare(strict_types=1);


namespace App\Api\Router;


use App\Api\Response\ResponseInterface;


class RouteHandler extends \App\Util\CallbackHandler {


  public function __invoke(...$args): mixed {
    $parameters = array_change_key_case($args[0]);
    $arguments = [];

    foreach($this->getReflection()->getParameters() as $Parameter) {
      $Type = $Parameter->getType();

      if(($Type instanceof \ReflectionNamedType) AND $Type->isBuiltin()) {
        $value = $parameters[strtolower($Parameter->getName())];

        //array, callable, null, object, string, iterable, mixed, never, void,
        $arguments[$Parameter->getPosition()] = match($Type->getName()) {
          "int" => intval($value),
          "float" => floatval($value),
          "string" => strval($value),
          "bool", "false", "true" => boolval($value),
          "array" => (array)$value,

          // @TODO dořešit DI
          default => null
        };
      }

    }

    return parent::__invoke(...$arguments);
  }
}