<?php
declare(strict_types=1);

if(php_sapi_name() !== 'cli-server') {
  http_response_code(500);
  echo "<h1>Error 500</h1><p>Only cli-server is supported.</p>";
  exit(0);
};

(function() {
  $directoryMap = [
    "/public" => realpath(__DIR__ . "/../public")
  ];

  $requestFile = $_SERVER['PHP_SELF'] ?? "";
  if(strlen($requestFile)) {
    foreach($directoryMap as $prefix => $directory) {
      if(str_starts_with($requestFile, $prefix)) {
        $file = $directory . DIRECTORY_SEPARATOR . substr($requestFile, strlen($prefix) + 1);


        if(!file_exists($file) or !is_file($file)) {
          http_response_code(404);
          echo "<h1>Error 404</h1><p>File <em>{$requestFile}</em> not found.</p>";
          exit(0);
        }

        $type = mime_content_type($file);
        if($type) {
          header("Content-Type: {$type}");
          readfile($file, false);
        }
        exit(0);
      }
    }
  }

  return require(__DIR__ . '/index.php');
})();