<?php
declare(strict_types=1);

namespace App\Controller\Api;


use App\Api\Response\ResponseInterface;
use App\Entity\MessageEntity;
use App\Entity\Factory\MessageEntityFactory;
use App\Repository\MessageRepositoryMySQL;
use DI\Attribute\Inject;


/**
 * @template E of MessageEntity
 */
class MessageController extends \App\Controller\Controller {


  #[Inject]
  private readonly MessageRepositoryMySQL $messageRepo;

  private readonly MessageEntityFactory $messageEntityFactory;


  public function __construct(MessageEntityFactory $MessageEntityFactory) {
    $this->messageEntityFactory = $MessageEntityFactory;
  }


}