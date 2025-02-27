<?php
declare(strict_types=1);

if(php_sapi_name() !== 'cli-server') {
  http_response_code(500);
  echo "<h1>Error 500</h1><p>Only cli-server is supported.</p>";
  exit(0);
};

(function() {
  $directoryMap = [
    "/favicon.ico" => realpath(__DIR__ . "/../public/favicon.ico"),
    "/public" => realpath(__DIR__ . "/../public")
  ];
  $mimeTypeMap = [
    "css" => "text/css",
    "js" => "text/javascript",
    "html" => "text/html",
  ];

  $requestFile = $_SERVER['PHP_SELF'] ?? "";

  if(strlen($requestFile)) {
    foreach($directoryMap as $prefix => $directory) {
      if(str_starts_with($requestFile, $prefix)) {
        $file = rtrim($directory . DIRECTORY_SEPARATOR . substr($requestFile, strlen($prefix) + 1), DIRECTORY_SEPARATOR);

        if(!file_exists($file) or !is_file($file)) {
          http_response_code(404);
          echo "<h1>Error 404</h1><p>File <em>{$requestFile}</em> not found.</p>";
          exit(0);
        }

        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $mimeType = $mimeTypeMap[$extension] ?? mime_content_type($file);

        if($mimeType) {
          header("Content-Type: {$mimeType}");
        }

        header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Pragma: no-cache');

        readfile($file, false);
        exit(0);
      }
    }
  }

  return require(__DIR__ . '/index.php');
})();