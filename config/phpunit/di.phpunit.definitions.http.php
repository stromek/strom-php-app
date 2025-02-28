<?php
declare(strict_types=1);

use App\Http\Session\Handler\SessionHandlerDefault;
use App\Http\Session\Storage\SessionStorageInterface;
use App\Http\Session\Storage\SessionStorageMemory;

return [
  SessionHandlerInterface::class => Di\autowire(SessionHandlerDefault::class),
  SessionStorageInterface::class => Di\autowire(SessionStorageMemory::class),
];