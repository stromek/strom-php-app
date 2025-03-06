<?php
declare(strict_types=1);


use App\Authentication\Provider\AuthBearerProvider;
use App\Authentication\Provider\AuthDevelopProvider;
use App\Authentication\Provider\AuthProviderInterface;
use Psr\Container\ContainerInterface;


return [
  AuthProviderInterface::class => function (ContainerInterface $Container): AuthProviderInterface {
    if(\App\Env\AppEnv::isDeveloper()) {
      return $Container->get(AuthDevelopProvider::class);
    }

    return $Container->get(AuthBearerProvider::class);
  }
];