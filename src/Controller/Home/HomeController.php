<?php
declare(strict_types=1);

namespace App\Controller\Home;



use App\Http\Enum\StatusCodeEnum;


class HomeController extends \App\Controller\Controller {


  private \App\Xml\XMLBuilder $xml;

  
  public function __construct(\App\Xml\XMLBuilder $xmlBuilder) {
    $this->xml = $xmlBuilder;
  }


  public function index(): \App\Api\Response\ResponseInterface {
    return $this->responseFactory->createResponseFromXML($this->xml, TEMPLATE_DIR."/home.index.xsl");
  }


  public function error404(): \App\Api\Response\ResponseInterface {
    return $this->responseFactory->createResponseFromXML($this->xml, TEMPLATE_DIR."/home.error404.xsl", StatusCodeEnum::STATUS_NOT_FOUND);
  }

}