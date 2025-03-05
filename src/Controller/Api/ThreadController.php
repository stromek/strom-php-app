<?php
declare(strict_types=1);

namespace App\Controller\Api;


use App\Api\Response\ResponseInterface;
use App\Api\Transformer\CustomerResponseTransformer;
use App\Entity\ThreadEntity;
use App\Entity\Factory\ThreadEntityFactory;
use App\Repository\MessageRepositoryMySQL;
use App\Repository\ThreadRepositoryMySQL;
use DI\Attribute\Inject;
use OpenApi\Attributes as OA;


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


  #[OA\Get(
    path: '/api/thread/{hash}/',
    description: 'Thread detail',
    tags: ['Thread'],
    responses: [
      new OA\Response(ref: "#/components/responses/401", response: 401),
      new OA\Response(ref: "#/components/responses/500", response: 500),
      new OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(allOf: [
          new OA\Schema(ref: "#/components/schemas/ResponseEntity"),
          new OA\Schema(properties: [
            new OA\Property(property: "payload", properties: [
              new OA\Property(property : "attributes", ref: "#/components/schemas/ThreadEntity")
            ])
          ])
        ]),
      ),
    ]
  )]
  #[OA\Parameter(
    name: 'hash',
    description: 'ThreadEntity hash',
    in: 'path',
    required: true,
    schema: new OA\Schema(ref: "#/components/schemas/ThreadEntityHash")
  )]
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