<?php
declare(strict_types=1);

namespace App\Exception;


use App\Interface\ApiErrorInterface;


class ApiException extends AppException implements ApiErrorInterface {

  /**
   * @var mixed|null
   */
  private mixed $details;


  public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null, mixed $details = null) {
    parent::__construct($message, $code, $previous);

    $this->setDetails($details);
  }


  public function setDetails(mixed $details): void {
    $this->details = $details;
  }


  public function getUserCode(): string {
    $RefClass = new \ReflectionClass($this);
    $userCode = array_search($this->getCode(), $RefClass->getConstants());

    return ($userCode !== false) ? $userCode : $this->getStatusCodeEnum()->getText();
  }


  public function getUserMessage(): string {
    return $this->getMessage();
  }


  public function getDetails(): mixed {
    return $this->details;
  }

}