<?php
declare(strict_types=1);

namespace App\Debugger;


class AppPanel extends BasePanel {


  function getTabName(): string {
    return "APP";
  }

  function getTabContent(): string {
    $table = $this->createTableToggle("Constants", [
      ["ROOT_DIR", constant("ROOT_DIR")],
      ["CONFIG_DIR", constant("CONFIG_DIR")],
      ["PUBLIC_DIR", constant("PUBLIC_DIR")],
      ["SRC_DIR", constant("SRC_DIR")],
      ["TMP_DIR", constant("TMP_DIR")],
      ["TEMPLATE_DIR", constant("TEMPLATE_DIR")],
    ]);

    return $table;
  }


}

