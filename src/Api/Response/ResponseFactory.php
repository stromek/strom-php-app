<?php
declare(strict_types=1);

namespace App\Api\Response;

use App\Api\Response\Filter\ResponseFilterFactory;
use App\Http\Enum\StatusCodeEnum;
use Tracy\Debugger;


class ResponseFactory {

  private \App\Api\Request\Request $request;


  public function __construct(\App\Api\Request\Request $httpRequest) {
    $this->request = $httpRequest;
  }

//  /**
//   * Vytvoření obecné odpovědi
//   *
//   * @param mixed $payload
//   * @param StatusCodeEnum $statusCodeEnum
//   * @return ResponseInterface
//   */
//  public function createResponseWithFilter(mixed $payload, StatusCodeEnum $statusCodeEnum = StatusCodeEnum::STATUS_OK): ResponseInterface {
//    $Filter = $this->createResponseFilter();
//
//    return $this->createResponse(
//      $statusCodeEnum,
//      $Filter->transform($payload),
//      $Filter->contentType(),
//    );
//  }


  public function createResponse(StatusCodeEnum $statusCodeEnum, string $body, string|null $contentType): ResponseInterface {
    $headers = [];

    if($contentType) {
      $headers['Content-Type'] = $contentType;
    }

    return new Response($statusCodeEnum,$body, $headers);
  }


  public function createApiResponse(mixed $payload, StatusCodeEnum $statusCodeEnum = StatusCodeEnum::STATUS_OK): ResponseInterface {
    $Filter = ResponseFilterFactory::createFromFormat(ResponseFilterFactory::FORMAT_JSON);

    return $this->createResponse(
      $statusCodeEnum,
      $Filter->transform($this->createApiResponseStructure(null, null, $payload)),
      $Filter->contentType(),
    );
  }


  public function createApiResponseFromException(\Exception $Exception, ?StatusCodeEnum $statusCodeEnum = null, mixed $payload = null): ResponseInterface {
    $Filter = ResponseFilterFactory::createFromFormat(ResponseFilterFactory::FORMAT_JSON);

    if(is_null($statusCodeEnum) AND $Exception instanceof \App\Interface\AppErrorInterface) {
      $statusCodeEnum = $Exception->getStatusCodeEnum();
    }

    return $this->createResponse(
      $statusCodeEnum ?? StatusCodeEnum::STATUS_INTERNAL_SERVER_ERROR,
      $Filter->transform($this->createApiResponseStructure($Exception->getCode(), $Exception->getMessage(), $payload)),
      $Filter->contentType(),
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


//  private function createResponseFilter(): \App\Api\Response\Filter\ResponseFilterInterface {
//    $format = $this->request->getQuery("format") ?? "";
//
//    return ResponseFilterFactory::create(
//      is_array($format) ? "" : $format,
//      $this->request->getHeaderLine("accept")
//    );
//  }


  /**
   * @return array{error: array{code: ?int, text: ?string}, payload: mixed}
   */
  private function createApiResponseStructure(?int $errCode, ?string $errMessage, mixed $responsePayload): array {
    $Structure = new ResponseApiBodyStructure($errCode, $errMessage, $responsePayload);

    return $Structure->create();
  }

}