<?php
declare(strict_types=1);

namespace App\Debugger;


abstract class BasePanel implements \Tracy\IBarPanel {


  protected \DI\Container $container;


  public function __construct(\DI\Container $Container) {
    $this->container = $Container;
  }


  abstract function getTabName(): ?string;


  abstract function getTabContent(): ?string;


  public function getTabIcon(): ?string {
    return null;
  }

  public function getPanel(): string {
    $content = $this->getTabContent();

    if(!$content) {
      return "";
    }

    return $this->createContent("
      <h1>{$this->getTabName()}</h1>
      ". $content
    );
  }


  public function getTab(): ?string {
    $name = $this->getTabName();

    if(!$name) {
      return null;
    }

    $icon = $this->getTabIcon();

    return '
      <span title="">
        '.($icon?'<img src="'.$icon.'" />':'').'
        <span class="tracy-label">'.$name.'</span>
      </span>
     ';
  }


  protected function createCode(string $code): string {
    return "
      <pre>
      {$code}
      </pre>
    ";
  }


  protected function createContent(string $content): string {
    return "<div class='tracy-inner tracy-InfoPanel'>{$content}</div>";
  }


  /**
   * @param string $legend
   * @param array<int, array{0: string, 1: string}> $lines
   * @return string
   */
  protected function createTableToggle(?string $legend, array $lines): string {
    $uid = uniqid();

    $title = is_null($legend) ? "" : "<h2><a class=\"tracy-toggle tracy-collapsed\" data-tracy-ref=\"^div .tracy-InfoPanel-{$uid}\">{$legend} (".count($lines).")</a></h2>";

    return "
      {$title}
      <div class=\"tracy-InfoPanel-{$uid} tracy-collapsed\">
        ".$this->createTable($lines)."
      </div>
    ";
  }


  /**
   * @param array<int, array{0: string, 1: string}> $lines
   * @return string
   */
  protected function createTable(array $lines): string {
    $tableContent = [];

    foreach($lines as $value) {
      [$key, $value] = $value;

      $tableContent[] = "
        <tr>
          <td>{$key}</td>
          <td>{$value}</td>
        </tr>
      ";
    }

    return "<table><tbody>".implode(" ", $tableContent)."</tbody></table>";
  }

}
