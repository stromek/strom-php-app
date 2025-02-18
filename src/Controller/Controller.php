<?php
declare(strict_types=1);

namespace App\Controller;


abstract class Controller {

  #[\DI\Attribute\Inject]
  protected \App\Api\Response\ResponseFactory $responseFactory;
  
}