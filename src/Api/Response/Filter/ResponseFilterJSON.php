<?php
declare(strict_types=1);

namespace App\Api\Response\Filter;



class ResponseFilterJSON implements ResponseFilterInterface {

  public function contentType(): string {
    return "application/json";
  }
  

  public function transform(mixed $body): string {
    return json_encode($body, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
  }

}