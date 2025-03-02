<?php
declare(strict_types=1);

namespace App\Api\Response\Filter;



abstract class ResponseFilterFactory  {

  const FORMAT_XML = "xml";
  const FORMAT_JSON = "json";

  private const ACCEPT_MAP = [
    "text/xml" => ResponseFilterXML::class,
    "application/json" => ResponseFilterJSON::class,
  ];

  private const FORMATS_MAP = [
    self::FORMAT_XML => ResponseFilterXML::class,
    self::FORMAT_JSON => ResponseFilterJSON::class,
  ];


  /**
   * @param self::FORMAT_* $fileFormat
   * @param key-of<self::ACCEPT_MAP> $acceptHeader
   * @return ResponseFilterInterface
   */
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

  /**
   * @param key-of<self::ACCEPT_MAP> $acceptHeader
   * @return ResponseFilterInterface|null
   */
  public static function createFromAcceptHeader(string $acceptHeader): ?ResponseFilterInterface {
    foreach(self::ACCEPT_MAP as $acceptType => $class) {
      if(str_contains($acceptHeader, $acceptType)) {
        return new $class();
      }
    }

    return null;
  }


  /**
   * @param self::FORMAT_* $fileFormat
   **/
  public static function createFromFormat(string $fileFormat): ?ResponseFilterInterface {
    foreach(self::FORMATS_MAP as $acceptType => $class) {
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