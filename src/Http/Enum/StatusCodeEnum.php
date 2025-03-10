<?php
declare(strict_types=1);

namespace App\Http\Enum;


enum StatusCodeEnum: int {
  
  case STATUS_CONTINUE = 100;
  case STATUS_SWITCHING_PROTOCOLS = 101;
  case STATUS_PROCESSING = 102;
  case STATUS_EARLY_HINTS = 103;
  
  case STATUS_OK = 200;
  case STATUS_CREATED = 201;
  case STATUS_ACCEPTED = 202;
  case STATUS_NON_AUTHORITATIVE_INFORMATION = 203;
  case STATUS_NO_CONTENT = 204;
  case STATUS_RESET_CONTENT = 205;
  case STATUS_PARTIAL_CONTENT = 206;
  case STATUS_MULTI_STATUS = 207;
  case STATUS_ALREADY_REPORTED = 208;
  case STATUS_IM_USED = 226;
  
  case STATUS_MULTIPLE_CHOICES = 300;
  case STATUS_MOVED_PERMANENTLY = 301;
  case STATUS_FOUND = 302;
  case STATUS_SEE_OTHER = 303;
  case STATUS_NOT_MODIFIED = 304;
  case STATUS_USE_PROXY = 305;
  case STATUS_RESERVED = 306;
  case STATUS_TEMPORARY_REDIRECT = 307;
  case STATUS_PERMANENT_REDIRECT = 308;
  
  case STATUS_BAD_REQUEST = 400;
  case STATUS_UNAUTHORIZED = 401;
  case STATUS_PAYMENT_REQUIRED = 402;
  case STATUS_FORBIDDEN = 403;
  case STATUS_NOT_FOUND = 404;
  case STATUS_METHOD_NOT_ALLOWED = 405;
  case STATUS_NOT_ACCEPTABLE = 406;
  case STATUS_PROXY_AUTHENTICATION_REQUIRED = 407;
  case STATUS_REQUEST_TIMEOUT = 408;
  case STATUS_CONFLICT = 409;
  case STATUS_GONE = 410;
  case STATUS_LENGTH_REQUIRED = 411;
  case STATUS_PRECONDITION_FAILED = 412;
  case STATUS_PAYLOAD_TOO_LARGE = 413;
  case STATUS_URI_TOO_LONG = 414;
  case STATUS_UNSUPPORTED_MEDIA_TYPE = 415;
  case STATUS_RANGE_NOT_SATISFIABLE = 416;
  case STATUS_EXPECTATION_FAILED = 417;
  case STATUS_IM_A_TEAPOT = 418;
  case STATUS_MISDIRECTED_REQUEST = 421;
  case STATUS_UNPROCESSABLE_ENTITY = 422;
  case STATUS_LOCKED = 423;
  case STATUS_FAILED_DEPENDENCY = 424;
  case STATUS_TOO_EARLY = 425;
  case STATUS_UPGRADE_REQUIRED = 426;
  case STATUS_PRECONDITION_REQUIRED = 428;
  case STATUS_TOO_MANY_REQUESTS = 429;
  case STATUS_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
  case STATUS_UNAVAILABLE_FOR_LEGAL_REASONS = 451;
  
  case STATUS_INTERNAL_SERVER_ERROR = 500;
  case STATUS_NOT_IMPLEMENTED = 501;
  case STATUS_BAD_GATEWAY = 502;
  case STATUS_SERVICE_UNAVAILABLE = 503;
  case STATUS_GATEWAY_TIMEOUT = 504;
  case STATUS_VERSION_NOT_SUPPORTED = 505;
  case STATUS_VARIANT_ALSO_NEGOTIATES = 506;
  case STATUS_INSUFFICIENT_STORAGE = 507;
  case STATUS_LOOP_DETECTED = 508;
  case STATUS_NOT_EXTENDED = 510;
  case STATUS_NETWORK_AUTHENTICATION_REQUIRED = 511;


  public function getText(): string {
    return substr($this->name, 7);
  }

}