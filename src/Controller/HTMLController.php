<?php
declare(strict_types=1);

namespace App\Controller;


/**
 * @phpstan-type AppHtmlElements array<int, array{elementName: string, attributes: array<string, string>, content: string}>
 */
class HTMLController extends Controller {

  protected \App\Xml\XMLBuilder $xml;

  const ASSETS_URL = "/public";

  protected string $title = "";

  /**
   * @var AppHtmlElements
   */
  private array $headElements = [];

  /**
   * @var AppHtmlElements
   */
  private array $bodyElements = [];


  public function __construct(\App\Xml\XMLBuilder $xmlBuilder) {
    $this->xml = $xmlBuilder;
  }


  public function setTitle(string $title): void {
    $this->title = $title;
  }


  /**
   * @param string $filename relative to /public dir
   * @return void
   */
  public function addStyle(string $filename): void {
    $this->addHeadElement("link", [
      "rel" => "stylesheet",
      "type" => "text/css",
      "href" => $this->getAssetFilePath($filename)
    ]);
  }


  public function addScript(string $filename): void {
    $this->addHeadElement("script", [
      "type" => "text/javascript",
      "src" => $this->getAssetFilePath($filename)
    ]);
  }

  public function addScriptBody(string $filename): void {
    $this->addBodyElement("script", [
      "type" => "text/javascript",
      "src" => $this->getAssetFilePath($filename)
    ]);
  }


  public function addMeta(string $name, string $content): void {
    $this->addHeadElement("meta", [
      "name" => $name,
      "content" => $content
    ]);
  }


  protected function renderHTML(string $stylesheet): \App\Api\Response\ResponseInterface {
    $this->xml->addData("head", [
      "title" => $this->title,
      "elements" => $this->headElements,
    ]);

    $this->xml->addData("body", [
      "elements" => $this->bodyElements,
    ]);

    $Response = $this->responseFactory->createResponseFromXML($this->xml, TEMPLATE_DIR.DIRECTORY_SEPARATOR.$stylesheet);
    $Response->addHeader("Content-Type", "text/html");
    return $Response;
  }


  private function getAssetFilePath(string $filename): string {
    $publicUrl = self::ASSETS_URL . "/" . (str_starts_with($filename, "/") ? substr($filename, 1) : $filename);
    $pathname = realpath(PUBLIC_DIR . (str_starts_with($filename, "/") ? $filename : DIRECTORY_SEPARATOR .$filename));

    if($pathname === false) {
      throw new \RuntimeException("File '{$filename}' in '".PUBLIC_DIR."' not found");
    }

    return $publicUrl."?".http_build_query(["m" => filemtime($pathname)]);
  }


  /**
   * @param string $elementName
   * @param array<string, string> $attributes
   * @param string $content
   */
  public function addHeadElement(string $elementName, array $attributes = [], string $content = ""): void {
    $this->headElements[] = [
      "elementName" => $elementName,
      "attributes" => $attributes,
      "content" => $content,
    ];
  }

  /**
   * @param string $elementName
   * @param array<string, string> $attributes
   * @param string $content
   */
  public function addBodyElement(string $elementName, array $attributes = [], string $content = ""): void {
    $this->bodyElements[] = [
      "elementName" => $elementName,
      "attributes" => $attributes,
      "content" => $content,
    ];
  }
}