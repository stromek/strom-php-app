<?php
declare(strict_types=1);

namespace App\Api\Response\Filter;



class ResponseFilterJSONApi extends ResponseFilterJSON {

  /**
   * @param array<string, string> $headers
   * @return array<string, string>
   */
  public function transformHeaders(array $headers = []): array {
    $headers['Access-Control-Allow-Origin'] = "*";

    return $headers;
  }
}