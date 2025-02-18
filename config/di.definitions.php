<?php
declare(strict_types=1);

return [
  ...require(__DIR__.'/di.definitions.storage.php'),
  ...require(__DIR__.'/di.definitions.http.php'),
  ...require(__DIR__.'/di.definitions.repository.php'),
];