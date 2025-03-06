<?php
declare(strict_types=1);

namespace App\Controller\App;



use App\Http\Enum\StatusCodeEnum;


class AppController extends \App\Controller\HTMLController {

  public function __construct(\App\Xml\XMLBuilder $xmlBuilder) {
    parent::__construct($xmlBuilder);
  }


  public function index(): \App\Api\Response\ResponseInterface {
    $this->setTitle("APP");
    $this->addScriptBody("/app/client/client.js");

    return $this->renderHTML("App.index.xsl");
  }

  public function example(): \App\Api\Response\ResponseInterface {
    $this->setTitle("Example");

    $this->xml->addData("clientKey", "aVYWB3h7xdhAYWB3jf9OrByM3PLdBfeguuNonxobBkD3dJS3qR");
    $this->xml->addData("clientSecret", "yGBQdqj8zgsnid1kqZujUuw0hgjCXNW8GUhyL1DVC6w91wm5dD");

    $this->xml->addData("snippetUrl", "http://localhost:8080/snippet.js");
//    $this->xml->addData("snippetUrl", "/public/app/snippet/snippet.js");

    return $this->renderHTML("App.example.xsl");
  }


  public function error404(): \App\Api\Response\ResponseInterface {
    $this->setTitle("Error 404");
    $this->addStyle("/css/appError.css");

    return $this->renderHTML("App.error404.xsl", StatusCodeEnum::STATUS_NOT_FOUND);
  }


}