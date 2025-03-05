<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Api\Response\ResponseInterface;
use App\Exception\AppException;


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
    $Exception = new AppException("Error 404 - Page not found", );
    return $this->responseFactory->createApiResponseFromException($Exception, \App\Http\Enum\StatusCodeEnum::STATUS_NOT_FOUND);
  }

}