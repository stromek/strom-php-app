<?php
declare(strict_types=1);

namespace App\Controller\Customer;


use OpenApi\Attributes as OA;
use App\Api\Response\ResponseInterface;
use App\Api\Transformer\CustomerResponseTransformer;
use App\Entity\CustomerEntity;
use App\Entity\Factory\CustomerEntityFactory;
use App\Repository\Customer\CustomerRepositoryMySQL;


/**
 * @template E of CustomerEntity
 */
class CustomerController extends \App\Controller\Controller {


  /**
   * @var CustomerRepositoryMySQL<E>
   */
  private CustomerRepositoryMySQL $repository;

  private CustomerResponseTransformer $customerResponseTransformer;

  private CustomerEntityFactory $customerEntityFactory;


  /**
   * @param CustomerRepositoryMySQL<E> $Repository
   */
  public function __construct(CustomerRepositoryMySQL $Repository, CustomerEntityFactory $CustomerEntityFactory, CustomerResponseTransformer $customerResponseTransformer) {
    $this->repository = $Repository;
    $this->customerEntityFactory = $CustomerEntityFactory;
    $this->customerResponseTransformer = $customerResponseTransformer;
  }

  #[OA\Get(path: '/api/data.json', operationId: 'getData')]
  #[OA\Response(response: '200', description: 'The data')]
  public function detail(int $id): ResponseInterface {
    $Customer = $this->repository->findByID($id);

    return $this->responseFactory->createApiResponse($this->customerResponseTransformer->transformCustomer($Customer));
  }


  /**
   * @param array<array-key, scalar> $data
   */
  public function create(array $data): ResponseInterface {
    $Customer = $this->customerEntityFactory->createCustomer($data);
    $this->repository->insertCustomer($Customer);

    return $this->responseFactory->createApiResponse($this->customerResponseTransformer->transformCustomer($Customer));
  }


  /**
   * @param int $id
   * @param array<array-key, scalar> $data
   */
  public function update(int $id, array $data = []): ResponseInterface {
    $Customer = $this->repository->findByID($id);
    // @TODO data
    $this->repository->updateCustomer($Customer);

    // @TODO
    return $this->responseFactory->createApiResponse(["update" => "OK"]);
  }


  public function delete(int $id): ResponseInterface {
    $Customer = $this->repository->findByID($id);
    $this->repository->deleteCustomer($Customer);

    // @TODO
    return $this->responseFactory->createApiResponse(["deleted" => "OK"]);
  }

}