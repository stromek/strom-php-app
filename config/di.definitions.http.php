<?php
declare(strict_types=1);

return [
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
      case "cli-server";
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
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