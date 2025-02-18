<?php
declare(strict_types=1);

namespace App\Api\Response\Filter;



class ResponseFilterXML implements ResponseFilterInterface {

  public function contentType(): string {
    return "text/xml; charset=utf-8";
  }
  

  public function transform(mixed $body): string {
    $Xml = new \App\Xml\XMLBuilder();

    if(is_iterable($body)) {
      foreach($body as $key => $value) {
        $Xml->addData($key, $value);
      }
    }else {
      $Xml->addData("response", $body);
    }

    return $Xml->__toString();

  }

}