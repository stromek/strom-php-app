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

}