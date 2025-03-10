<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Api\Response\ResponseInterface;
use App\Exception\ApiException;
use App\Exception\AppException;
use OpenApi\Attributes as OA;


/**
 * @phpstan-import-type RequestCustomer from \App\Middleware\AuthenticationCustomerMiddleware
 */
class ApiController extends \App\Controller\Controller {


  /**
   * @return RequestCustomer
   */
  protected function getCurrentCustomer(): array {
    return $this->request['customer'];
  }


  protected function getCurrentCustomerID(): int {
    return $this->getCurrentCustomer()['id'];
  }


  public function error404(): ResponseInterface {
    return $this->responseFactory->createApiResponseFromException(
      new ApiException("Error 404 - Page not found"),
      \App\Http\Enum\StatusCodeEnum::STATUS_NOT_FOUND,
      [
        "message" => "Path not found",
        "path" => $this->request->getUri()->getPath()
      ]
    );
  }
}