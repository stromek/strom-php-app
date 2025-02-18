<?php
declare(strict_types=1);

namespace App\Api\Response;

interface ResponseInterface {

  
  public function send(): void;


  public function sendHeaders(): void;


  public function sendBody(): void;

}