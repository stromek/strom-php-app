<?php
declare(strict_types=1);

use App\Env\AppEnv;
use Tracy\Debugger;


Debugger::$logDirectory = null;
Debugger::$strictMode = true;
//Debugger::enable(Debugger::Production);
Debugger::enable(Debugger::Development);


/**
 * @tracySkipLocation
 */
function vd(mixed $var): void {
  static $stylesPrint = false;

  if(php_sapi_name() == 'cli') {
    Debugger::dump($var);
    return;
  }

  if(!$stylesPrint) {
    $stylesPrint = true;
    echo "
      <style>
        tracy-div.vd {
          display:block;
          background-color: #eee;
          padding: 10px;
          margin: 10px 0;
          border-radius: 3px;
        }
        div.vdc {
          overflow: auto;
          max-height: 150px;
        }
        table.vd {
          width: 100%;
          font-size: 11px;
          font-family: monospace;
          margin: 0;
          background-color: white;
        }
        table.vd thead {
          background-color: #fdf9e2;
          border-bottom: 2px solid #ddd;
        }
        table.vd tbody {
          
        }
        table.vd tbody tr:nth-child(even) {
          background-color: #f9f9f9;
        }
        table.vd tbody tr:hover{
          background-color: #ddd;
        }
        table.vd td, table.vd th {
          padding:5px 15px;
          border-bottom: 1px solid #aaa;
        }
      </style>
    ";
  }

  echo "<tracy-div class=\"vd\">";
  echo "<div class=\"vdc\">";
  echo "<table class=\"vd\">";
  echo "<thead>";
  echo "
    <tr>
      <th>#</th>
      <th>File:line</th>
      <th>Method/Function call</th>
      <th>Source preview</th>
    </tr>
  ";
  echo "</thead>";
  echo "<tbody>";

  $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
  foreach($backtrace as $i => $item) {
    /** @var \ReflectionMethod|ReflectionFunction|null $Reflection */

    echo "<tr>";
    echo "<td style='text-align: right''>".(count($backtrace) - $i)."</td>";
    echo "<td>".((isset($item['file']) AND isset($item['line'])) ? \Tracy\Helpers::editorLink($item['file'], $item['line']) : "?")."</td>";

    $Reflection = null;
    $functionName = "";
    $parameters = [];
    $isClosure = str_contains($item['function'], '{closure}');

    if($isClosure) {
      $functionName = "{closure}";

    }elseif(isset($item['class'])) {
      $Reflection = new \ReflectionMethod($item['class'], $item['function']);
      $functionName = $item['class'].($item['type']??"?").$item['function'];

    }elseif($item['function']) {
      $Reflection = new \ReflectionFunction($item['function']);
      $functionName = $item['function'];

    }else {
      $functionName = "?";
    }


    if($Reflection?->getNumberOfParameters() > 0) {
      foreach($Reflection->getParameters() as $Parameter) {
        $parameterString = "";

        if($Parameter->allowsNull()) {
          $parameterString .= "?";
        }
        $parameterString .= $Parameter->getType();
        $parameterString .= $Parameter->canBePassedByValue() ? " " : " &";
        $parameterString .= "\$".$Parameter->getName();

        $parameters[] = $parameterString;
      }
    }

    echo "<td>{$functionName}(".implode(", ", $parameters).")</td>";

    if(isset($item['file']) AND isset($item['line']) AND file_exists($item['file'])) {
      $k = 0;
      $lines = implode("\n", array_slice(file($item['file']) ?: [], max(0, $item['line'] - 1 - $k), 1 + $k));

      echo "<td>".nl2br($lines)."</td>";
    }


    echo "</tr>";
  }

  echo "</tbody>";
  echo "</table>";
  echo "</div>";
  Debugger::dump($var);
  echo "</tracy-div>";
}

(function() {
  if(AppEnv::isDeveloper()) {
    $Container = \App\Factory\ContainerFactory::create();

    $Panel = new Dibi\Bridges\Tracy\Panel();
    $Panel->register($Container->get(\Dibi\Connection::class));
  }
})();
