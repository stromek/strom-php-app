<?php
declare(strict_types=1);

namespace App\Controller\Docs;

use App\Api\Response\ResponseInterface;


class DocsController extends \App\Controller\HTMLController {

  private \OpenApi\Annotations\OpenApi $openApi;

  public function __construct(\App\Xml\XMLBuilder $xmlBuilder) {
    parent::__construct($xmlBuilder);
    $openApi = \OpenApi\Generator::scan([SRC_DIR]);

    if(!$openApi) {
      throw new \App\Controller\ControllerException("OpenAPI generator failed");
    }

    $this->openApi = $openApi;
  }


  public function index(): ResponseInterface {
    return $this->renderHTML("Docs.index.xsl");
  }


  public function swagger(): ResponseInterface {
    $Response =  $this->responseFactory->createResponse(
      \App\Http\Enum\StatusCodeEnum::STATUS_OK,
      $this->openApi->toJson(),
      "application/json"
    );

    $Response->addHeader("Content-Disposition", "inline; filename=\"swagger.json\"");
    $Response->addHeader("Access-Control-Allow-Origin", "*");

    return $Response;
  }


}