<?php
declare(strict_types=1);

namespace App\Controller\Api;


use App\Api\Response\ResponseInterface;
use App\Api\Transformer\CustomerResponseTransformer;
use App\Entity\CustomerEntity;
use App\Entity\Factory\CustomerEntityFactory;
use App\Repository\CustomerRepositoryMySQL;
use App\Repository\UserRepositoryMySQL;
use DI\Attribute\Inject;
use OpenApi\Attributes as OA;


/**
 * @template E of CustomerEntity
 */
class CustomerController extends \App\Controller\Api\ApiController {


  /**
   * @var CustomerRepositoryMySQL<E>
   */
  #[Inject]
  private readonly CustomerRepositoryMySQL $customerRepo;

  #[Inject]
  private readonly UserRepositoryMySQL $userRepo;

  private readonly CustomerEntityFactory $customerEntityFactory;


  public function __construct(CustomerEntityFactory $CustomerEntityFactory) {
    $this->customerEntityFactory = $CustomerEntityFactory;
  }



  #[OA\Get(path: '/api/customer/', operationId: 'getData')]
  #[OA\Response(response: '200', description: 'The data')]
  public function detail(): ResponseInterface {
    $Customer = $this->customerRepo->findByID($this->getCurrentCustomerID());

    return $this->responseFactory->createApiResponse($this->entityTransformer->transformEntity($Customer));
  }


  public function listOfUsers(): ResponseInterface {
    $users = $this->userRepo->findAllByCustomerID($this->getCurrentCustomerID());

    return $this->responseFactory->createApiResponse(
      $this->entityTransformer->transformEntityList($users)
    );
  }


  public function userDetail(string $hash): ResponseInterface {
    $User = $this->userRepo->findByCustomerIDAndHash($this->getCurrentCustomerID(), $hash);

    return $this->responseFactory->createApiResponse($this->entityTransformer->transformEntity($User));
  }



//  /**
//   * @param array<array-key, scalar> $data
//   */
//  public function create(array $data): ResponseInterface {
//    $Customer = $this->customerEntityFactory->createCustomer($data);
//    $this->repository->insertCustomer($Customer);
//
//    return $this->responseFactory->createApiResponse($this->customerResponseTransformer->transformCustomer($Customer));
//  }
//
//
//  /**
//   * @param int $id
//   * @param array<array-key, scalar> $data
//   */
//  public function update(int $id, array $data = []): ResponseInterface {
//    $Customer = $this->repository->findByID($id);
//    // @TODO data
//    $this->repository->updateCustomer($Customer);
//
//    // @TODO
//    return $this->responseFactory->createApiResponse(["update" => "OK"]);
//  }
//
//
//  public function delete(int $id): ResponseInterface {
//    $Customer = $this->repository->findByID($id);
//    $this->repository->deleteCustomer($Customer);
//
//    // @TODO
//    return $this->responseFactory->createApiResponse(["deleted" => "OK"]);
//  }

}