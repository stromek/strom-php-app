<?php
declare(strict_types=1);

namespace App\Api\Response\Filter;



class ResponseFilterHTML implements ResponseFilterInterface {

  public function contentType(): string {
    return "text/html; charset=utf-8";
  }
  

  public function transform(mixed $body): string {
    return strval($body);
  }

}