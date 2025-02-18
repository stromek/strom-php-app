<?php
declare(strict_types=1);

namespace App\Api\Request;

use App\Http\Enum\MethodEnum;


class Request implements RequestInterface {

  private \GuzzleHttp\Psr7\Request $request;

  
  public function __construct(\GuzzleHttp\Psr7\Request $Request) {
    $this->request = $Request;
  }


  public function getMethod(): \App\Http\Enum\MethodEnum {
    return MethodEnum::from($this->request->getMethod());
  }


  public function getUri(): \Psr\Http\Message\UriInterface {
    return $this->request->getUri();
  }

  /**
   * @return null|string|array<string, mixed>
   */
  public function getQuery(string $key): null|string|array {
    $values = [];
    parse_str($this->getUri()->getQuery(), $values);

    return $values[$key] ?? null;
  }


  public function getHeaderLine(string $header): string {
    return $this->request->getHeaderLine($header);
  }

}