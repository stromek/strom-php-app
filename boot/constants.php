<?php
declare(strict_types=1);

define("ROOT_DIR", realpath(__DIR__."/.."));

define("CONFIG_DIR", realpath(ROOT_DIR."/config"));
define("PUBLIC_DIR", realpath(ROOT_DIR."/public"));

define("SRC_DIR", realpath(ROOT_DIR."/src"));
define("TMP_DIR", realpath(ROOT_DIR."/tmp"));
define("TEMPLATE_DIR", realpath(ROOT_DIR."/template"));