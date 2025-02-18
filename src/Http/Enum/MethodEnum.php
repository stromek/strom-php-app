<?php
declare(strict_types=1);

namespace App\Http\Enum;


enum MethodEnum: string {
  
  case GET = 'GET';

  case POST = 'POST';

  case PUT = 'PUT';

  case DELETE = 'DELETE';

  case OPTIONS = 'OPTIONS';

  case PATCH = 'PATCH';

  case HEAD = 'HEAD';

  case TRACE = 'TRACE';

  case CONNECT = 'CONNECT';

}