<?php
declare(strict_types=1);

namespace App\Controller;


use DI\Attribute\Inject;


abstract class Controller {

  /**
   * @var \App\Api\Request\RequestInterface<array-key, mixed>
   */
  #[Inject]
  protected \App\Api\Request\RequestInterface $request;

  #[Inject]
  protected \App\Api\Response\ResponseFactory $responseFactory;

  #[Inject]
  protected \App\Http\Session\Session $session;

  #[Inject]
  protected \App\Api\Transformer\EntityResponseTransformer $entityTransformer;
  
}