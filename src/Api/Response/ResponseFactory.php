<?php
declare(strict_types=1);

namespace App\Api\Response;

use App\Api\Response\Filter\ResponseFilterFactory;
use App\Api\Response\Filter\ResponseFilterJSON;
use App\Api\Response\Filter\ResponseFilterJSONApi;
use App\Api\Response\Structure\ApiResponseStructure;
use App\Http\Enum\StatusCodeEnum;


class ResponseFactory {

  private \App\Api\Request\Request $request;


  public function __construct(\App\Api\Request\Request $httpRequest) {
    $this->request = $httpRequest;
  }


  /**
   * @param array<string, string> $headers
   */
  public function createResponse(StatusCodeEnum $statusCodeEnum, string $body, string|null $contentType, array $headers = []): ResponseInterface {
    if($contentType) {
      $headers['Content-Type'] = $contentType;
    }

    return new Response($statusCodeEnum,$body, $headers);
  }


  /**
   * @param mixed $data
   * @param array<array-key, mixed> $meta
   */
  public function createApiResponse(mixed $data, array $meta = [], StatusCodeEnum $statusCodeEnum = StatusCodeEnum::STATUS_OK): ResponseInterface {
    $Filter = new ResponseFilterJSONApi();

    $Body = new ApiResponseStructure(
      ApiResponseStructure::STATUS_SUCCESS,
      $data,
      $meta,
      null,
      null,
      null
    );

    $correlation_id = $this->request->getHeaderLine("Correlation_id");
    if($correlation_id) {
      $Body->addMeta("correlation_id", $correlation_id);
    }


    return $this->createResponse(
      $statusCodeEnum,
      $Filter->transform($Body->create()),
      $Filter->contentType(),
      $Filter->transformHeaders()
    );
  }


  public function createApiResponseFromException(\Exception $Exception, ?StatusCodeEnum $statusCodeEnum = null, mixed $details = null): ResponseInterface {
    $Filter = new ResponseFilterJSONApi();

    if(is_null($statusCodeEnum) AND $Exception instanceof \App\Interface\AppErrorInterface) {
      $statusCodeEnum = $Exception->getStatusCodeEnum();
    }elseif(is_null($statusCodeEnum)) {
      $statusCodeEnum = StatusCodeEnum::STATUS_INTERNAL_SERVER_ERROR;
    }

    $errorCode = $statusCodeEnum->getText();
    $errorMessage = null;
    $errorDetails = $details;

    if($Exception instanceof \App\Interface\ApiErrorInterface) {
      $errorCode = $Exception->getUserCode();
      $errorMessage = $Exception->getUserMessage();
      $errorDetails = array_merge((array)$Exception->getDetails(), $errorDetails ?? []);
    }


    $Body = new ApiResponseStructure(
      ApiResponseStructure::STATUS_ERROR,
      null,
      [],
      $errorCode,
      $errorMessage,
      $errorDetails
    );

    return $this->createResponse(
      $statusCodeEnum,
      $Filter->transform($Body->create()),
      $Filter->contentType(),
      $Filter->transformHeaders()
    );
  }


  public function createResponseFromXML(\App\Xml\XMLBuilder $XML, string $template, StatusCodeEnum $statusCodeEnum = StatusCodeEnum::STATUS_OK): ResponseInterface {
    return $this->createResponse($statusCodeEnum, $XML->xslTransform($template), "text/html");
  }
  

  public function createResponseFromException(\Exception $Exception, ?StatusCodeEnum $statusCodeEnum = null): ResponseInterface {
    if(is_null($statusCodeEnum) AND $Exception instanceof \App\Interface\AppErrorInterface) {
      $statusCodeEnum = $Exception->getStatusCodeEnum();
    }

    return $this->createResponse(
      $statusCodeEnum ?? StatusCodeEnum::STATUS_INTERNAL_SERVER_ERROR,
      $Exception->getMessage(),
      "text/html",
    );
  }

}