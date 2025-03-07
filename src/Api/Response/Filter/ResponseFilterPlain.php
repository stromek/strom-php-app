<?php
declare(strict_types=1);

namespace App\Api\Response\Filter;


class ResponseFilterPlain implements ResponseFilterInterface {

  public function contentType(): null {
    return null;
  }
  

  public function transform(mixed $body): string {
    return strval($body);
  }

  /**
   * @param array<string, string> $headers
   * @return array<string, string>
   */
  public function transformHeaders(array $headers = []): array {
    return $headers;
  }

}