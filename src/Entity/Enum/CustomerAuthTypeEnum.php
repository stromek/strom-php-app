<?php
declare(strict_types=1);

namespace App\Entity\Enum;


enum CustomerAuthTypeEnum: string {

  case HTTP_BEARER = "httpBearer";

  case HTTP_REFERER = "httpReferer";

  case REMOTE_ADDRESS = "remoteAddress";
}