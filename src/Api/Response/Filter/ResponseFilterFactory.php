<?php
declare(strict_types=1);

namespace App\Api\Response\Filter;



abstract class ResponseFilterFactory  {

  private const ACCEPT_MAP = [
    "text/xml" => ResponseFilterXML::class,
    "application/json" => ResponseFilterJSON::class,
  ];

  private const FORMAT_MAP = [
    "xml" => ResponseFilterXML::class,
    "json" => ResponseFilterJSON::class,
  ];


  public static function create(string $fileFormat, string $acceptHeader): ResponseFilterInterface {
    $Filter = self::createFromFormat($fileFormat);
    if($Filter) {
      return $Filter;
    }

    $Filter = self::createFromAcceptHeader($acceptHeader);
    if($Filter) {
      return $Filter;
    }

    return self::createDefault();
  }


  public static function createFromAcceptHeader(string $acceptHeader): ?ResponseFilterInterface {
    foreach(self::ACCEPT_MAP as $acceptType => $class) {
      if(str_contains($acceptHeader, $acceptType)) {
        return new $class();
      }
    }

    return null;
  }


  public static function createFromFormat(string $fileFormat): ?ResponseFilterInterface {
    foreach(self::FORMAT_MAP as $acceptType => $class) {
      if($fileFormat === $acceptType) {
        return new $class();
      }
    }

    return null;
  }
  

  public static function createDefault(): ResponseFilterInterface {
    return new ResponseFilterJSON();
  }


}