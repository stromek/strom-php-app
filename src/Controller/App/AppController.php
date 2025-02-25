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
    $this->addScriptBody("/dist/bundle.js");

    return $this->renderHTML("App.index.xsl");
  }


  public function error404(): \App\Api\Response\ResponseInterface {
    $this->setTitle("Error 404");
    $this->addStyle("/css/appError.css");

    return $this->renderHTML("App.error404.xsl");
  }

}