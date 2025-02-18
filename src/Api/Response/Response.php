<?php
declare(strict_types=1);

namespace App\Api\Response;

use App\Http\Enum\StatusCodeEnum;


class Response implements ResponseInterface {

  /**
   * @var array<string, string>
   */
  private array $headers = [];

  private string $body;

  private StatusCodeEnum $status;


  /**
   * @param array<string, string> $headers
   */
  public function __construct(StatusCodeEnum $Status, string $body, array $headers = []) {
    $this->status = $Status;
    $this->addHeaders($headers);

    $this->body = $body;
  }

  /**
   * @param array<string, string> $headers
   */
  public function addHeaders(array $headers): void {
    foreach ($headers as $header => $value) {
      $this->addHeader($header, $value);
    }
  }


  public function addHeader(string $name, string $value): void {
    $this->headers[$name] = $value;
  }


  public function hasHeader(string $name): bool {
    return isset($this->headers[$name]);
  }


  public function getHeader(string $name): ?string {
    return $this->headers[$name] ?? null;
  }


  public function removeHeader(string $name): void {
    unset($this->headers[$name]);
  }


  public function sendHeaders(): void {
    http_response_code($this->status->value);
    foreach($this->headers as $name => $value) {
      Header("{$name}: {$value}");
    }
  }


  public function sendBody(): void {
    echo $this->body;
  }
  

  public function send(): void {
    $this->sendHeaders();
    $this->sendBody();
  }

}