<?php
declare(strict_types=1);

namespace App\Api\Response;


class ResponseBodyStructure {

  private ?int $errCode;

  private ?string $errMessage;

  private mixed $payload;


  public function __construct(?int $errCode = null, ?string $errMessage = null, mixed $responsePayload = null) {
    $this->errCode = $errCode;
    $this->errMessage = $errMessage;
    $this->payload = $responsePayload;
  }


  /**
   * @return array{error: array{code: ?int, text: ?string}, payload: mixed}
   */
  public function create(): array {
    return [
      "error" => [
        "code" => $this->errCode,
        "text" => $this->errMessage,
      ],
      "payload" => $this->payload,
    ];
  }
}
