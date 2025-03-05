<?php
declare(strict_types=1);

namespace App\Controller\Api;


use App\Api\Response\ResponseInterface;
use App\Api\Transformer\CustomerResponseTransformer;
use App\Controller\ControllerException;
use App\Entity\ThreadEntity;
use App\Entity\Factory\ThreadEntityFactory;
use App\Repository\MessageRepositoryMySQL;
use App\Repository\ThreadRepositoryMySQL;
use DI\Attribute\Inject;


/**
 * @template E of ThreadEntity
 */
class ThreadController extends ApiController {


  #[Inject]
  private readonly ThreadRepositoryMySQL $threadRepo;

  #[Inject]
  private readonly MessageRepositoryMySQL $messageRepo;

  private readonly ThreadEntityFactory $threadEntityFactory;


  public function __construct(ThreadEntityFactory $ThreadEntityFactory) {
    $this->threadEntityFactory = $ThreadEntityFactory;
  }


  public function detailByHash(string $hash): ResponseInterface {
    $Thread = $this->threadRepo->findByCustomerIDAndHash($this->getCurrentCustomerID(), $hash);

    return $this->responseFactory->createApiResponse($this->entityTransformer->transformEntity($Thread));
  }

  public function listOfMessages(string $hash): ResponseInterface {
    $customer_id = $this->getCurrentCustomerID();
    $thread_id = $this->threadRepo->getIDByCustomerIDAndHash($customer_id, $hash);
    $messages = $this->messageRepo->findAllByCustomerIDAndThreadID($customer_id, $thread_id);

    return $this->responseFactory->createApiResponse(
      $this->entityTransformer->transformEntityList($messages)
    );
  }

}