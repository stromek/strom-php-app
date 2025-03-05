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
        $key = strtolower($Parameter->getName());
        if(isset($parameters[$key])) {
          // Parametr jek dispozici
          $value = $this->formatValue($Type, $parameters[$key]);

        }else if($Parameter->isDefaultValueAvailable()) {
          // parametr ma vychozi hodnotu
          $value = $Parameter->getDefaultValue();
        }else if ($Parameter->allowsNull()) {
          // parametr muze byt null
          $value = null;
        }else {
          // Hodnota parmetru chybi


          throw new RouterException("Cannot call route handler. Parameter #{$Parameter->getPosition()} '\$key' is mandatory for ".$this->getReflection()->__toString(). ". Cannot call router callback.");
        }

        //array, callable, null, object, string, iterable, mixed, never, void,
        $arguments[$Parameter->getPosition()] = $value;
      }

    }

    return parent::__invoke(...$arguments);
  }




  /**
   * @param \ReflectionNamedType $Type
   * @param mixed $value
   * @return array<array-key, mixed>|bool|float|int|string|null
   */
  private function formatValue(\ReflectionNamedType $Type, mixed $value): string|int|bool|array|null|float {
    return match ($Type->getName()) {
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