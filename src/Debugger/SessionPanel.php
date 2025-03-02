<?php
declare(strict_types=1);

namespace App\Debugger;


use App\Http\Session\Session;


class SessionPanel extends BasePanel {

  function getTabName(): string {
    return "Session";
  }

  function getTabContent(): ?string {
    if(!Session::isActive()) {
      return null;
    }

    $prefix = "<?php";
    $code = str_replace(htmlentities($prefix), "", highlight_string($prefix." ".var_export($_SESSION, true), true));

    return $this->createCode($code);
  }

}
