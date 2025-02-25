<?php
declare(strict_types=1);

namespace App\Controller\Api;

use OpenApi\Attributes as OA;


#[OA\Info(title: "My First API", version: "0.1")]
class ApiController extends \App\Controller\Controller {


  private ?\OpenApi\Annotations\OpenApi $openApi;

  public function __construct() {
    $this->openApi = \OpenApi\Generator::scan([SRC_DIR]);
  }

  public function index(): \App\Api\Response\ResponseInterface {

    return $this->responseFactory->createResponse(
      \App\Http\Enum\StatusCodeEnum::STATUS_OK,
      $this->openApi->toJson(),
      "application/json"
    );
  }


}