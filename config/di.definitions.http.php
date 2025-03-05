<?php
declare(strict_types=1);

use App\Http\Session\Session;
use App\Http\Session\Storage\SessionStorageInterface;
use Psr\Container\ContainerInterface;


return [
  // Session handler, ktery se stara o ukladani dat
  SessionHandlerInterface::class => DI\autowire(\App\Http\Session\Handler\SessionHandlerDynamoDB::class),

  
  // @TODO nahradit jinym session handlerem
  // Storage session, jakym způsobem se zapisuje
  SessionStorageInterface::class => function (): SessionStorageInterface {
    return new \App\Http\Session\Storage\SessionStorageDefault();
  },


  Session::class => function (ContainerInterface $Container): Session {
    return new Session(
      $Container->get(\App\Api\Request\Request::class),
      $Container->get(SessionHandlerInterface::class),
      $Container->get(SessionStorageInterface::class)
    );
  },


  \GuzzleHttp\Psr7\Request::class => function () {
    $headers = [];
    foreach ($_SERVER as $key => $value) {
      if(str_starts_with($key, 'HTTP_')) {
        $headerName = str_replace('_', '-', substr($key, 5));
        $headers[$headerName] = $value;
      }
    }

    $method = null;
    $uri = null;

    switch(php_sapi_name()) {
      // PHP build-in serve
      case "cli-server";
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        break;

      // PHP Unit..
      case "cli";
        $method = "cli";
        $uri = "/";
        break;

      default;
        $method = filter_input(INPUT_SERVER, "REQUEST_METHOD");
        $uri = filter_input(INPUT_SERVER, "REQUEST_URI");
    };

    return new \GuzzleHttp\Psr7\Request(
      $method,
      $uri,
      $headers,
      file_get_contents('php://input') ?: ""
    );
  },

  \App\Api\Request\RequestInterface::class => DI\autowire(\App\Api\Request\Request::class),

  \App\Api\Response\ResponseInterface::class => DI\autowire(\App\Api\Response\Response::class),
];