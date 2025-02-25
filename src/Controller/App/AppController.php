<?php
declare(strict_types=1);

namespace App\Controller\App;



use App\Http\Enum\StatusCodeEnum;


class AppController extends \App\Controller\HTMLController {

  public function __construct(\App\Xml\XMLBuilder $xmlBuilder) {
    parent::__construct($xmlBuilder);

    $this->addStyle("/css/app.css");
  }


  public function index(): \App\Api\Response\ResponseInterface {
    $this->setTitle("APP");

    return $this->renderHTML("App.index.xsl");
  }


  public function error404(): \App\Api\Response\ResponseInterface {
    $this->setTitle("Error 404");

    return $this->renderHTML("App.error404.xsl");
  }

}