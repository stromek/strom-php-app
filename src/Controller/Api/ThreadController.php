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
        content: new OA\JsonContent(properties: [
          new OA\Property(property: "status", type: "string", example: "success"),
          new OA\Property(property: "data", ref: "#/components/schemas/Entity:Thread"),
          new OA\Property(property: "meta", ref: "#/components/schemas/Response:Meta")
        ])
      )
    ]
  )]
  #[OA\Parameter(
    name: 'hash',
    description: 'ThreadEntity hash',
    in: 'path',
    required: true,
    schema: new OA\Schema(ref: "#/components/schemas/Entity:Thread:Hash")
  )]
  public function detailByHash(string $hash): ResponseInterface {
    $Thread = $this->threadRepo->findByCustomerIDAndHash($this->getCurrentCustomerID(), $hash);

    return $this->responseFactory->createApiResponse($this->entityTransformer->transformEntity($Thread));
  }



  #[OA\Get(
    path: '/api/thread/find/',
    description: 'Thread detail by code',
    tags: ['Thread'],
    responses: [
      new OA\Response(ref: "#/components/responses/401", response: 401),
      new OA\Response(ref: "#/components/responses/500", response: 500),
      new OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(properties: [
          new OA\Property(property: "status", type: "string", example: "success"),
          new OA\Property(property: "data", ref: "#/components/schemas/Entity:Thread"),
          new OA\Property(property: "meta", ref: "#/components/schemas/Response:Meta")
        ])
      )
    ]
  )]
  #[OA\Parameter(
    name: 'code', description: 'ThreadEntity code', in: 'query', required: true, example: 'ORDER-1'
  )]
  public function detailByCode(): ResponseInterface {
    $code = $this->request->getQuery("code");
    if(!is_string($code) OR !$code) {
      throw new \App\Controller\ControllerException("Missing parameter 'code'.");
    }

    $Thread = $this->threadRepo->findByCustomerIDAndCode($this->getCurrentCustomerID(), $code);

    return $this->responseFactory->createApiResponse($this->entityTransformer->transformEntity($Thread));
  }


  #[OA\Get(
    path: '/api/thread/{hash}/messages/',
    description: 'List of messages in thread',
    tags: ['Thread', 'Message'],
    responses: [
      new OA\Response(ref: "#/components/responses/401", response: 401),
      new OA\Response(ref: "#/components/responses/500", response: 500),
      new OA\Response(
        response: 200,
        description: 'Success',
        content: new OA\JsonContent(properties: [
          new OA\Property(property: "status", type: "string", example: "success"),
          new OA\Property(property: "data", type: "array", items: new OA\Items(ref: "#/components/schemas/Entity:Message")),
          new OA\Property(property: "meta", allOf: [
            new OA\Schema(allOf: [
              new OA\Property(ref: "#/components/schemas/Response:Meta"),
              new OA\Schema(properties: [
                new OA\Property(property: "paginator", ref: "#/components/schemas/Response:Meta:Paginator")
              ])
            ])
          ])
        ]),
      )
    ]
  )]
  #[OA\Parameter(
    name: 'hash',
    description: 'ThreadEntity hash',
    in: 'path',
    required: true,
    schema: new OA\Schema(ref: "#/components/schemas/Entity:Thread:Hash")
  )]
  public function listOfMessages(string $hash): ResponseInterface {
    $customer_id = $this->getCurrentCustomerID();
    $thread_id = $this->threadRepo->getIDByCustomerIDAndHash($customer_id, $hash);
    $messages = $this->messageRepo->findAllByCustomerIDAndThreadID($customer_id, $thread_id);

    return $this->responseFactory->createApiResponse(
      $this->entityTransformer->transformEntityList($messages)
    );
  }

}