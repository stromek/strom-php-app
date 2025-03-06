<?php
declare(strict_types=1);

namespace App\Api\Response\Structure;


class ApiResponseStructure {

  const STATUS_SUCCESS = "success";

  const STATUS_ERROR = "error";

  private readonly string $status;


  /**
   * @var array<array-key, mixed>
   */
  private array $meta = [];

  /**
   * @var mixed|null
   */
  private mixed $data;

  private ?string $errorCode;

  private ?string $errorMessage;

  /**
   * @var ?array<array-key, mixed>
   */
  private ?array $errorDetails;


  /**
   * @param self::STATUS_* $status
   * @param array<array-key, mixed> $meta
   * @param null|mixed $data
   * @param ?array<array-key, mixed> $errorDetails
   */
  public function __construct(string $status = self::STATUS_SUCCESS, mixed $data = null, array $meta = [], ?string $errorCode = null, ?string $errorMessage = null, ?array $errorDetails = null) {
    $this->status = $status;
    $this->data = $data;

    $this->meta = $meta;
    $this->meta['timestamp'] = time();

    $this->errorCode = $errorCode;
    $this->errorMessage = $errorMessage;
    $this->errorDetails = $errorDetails;
  }

  public function addMeta(string $key, mixed $value): void {
    $this->meta[$key] = $value;
  }


  /**
   * @return array{
   *   status: self::STATUS_*,
   *   error: ?array{code: string, message: string, details: array},
   *   data: mixed,
   *   meta: array<array-key, mixed>
   * }
   */
  public function create(): array {
    $response = [
      "status" => $this->status
    ];

    if($this->errorCode) {
      $response['error'] = [
        "code" => $this->errorCode,
        "message" => $this->errorMessage,
        "details" => $this->errorDetails,
      ];
    }

    $response['data'] = $this->data;
    $response['meta'] = $this->meta;

    return $response;
  }
}
