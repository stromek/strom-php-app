<?php
declare(strict_types=1);

namespace App\Api\Request;

/**
 * @template TKey of array-key
 * @template TValue mixed
 * @extends \ArrayAccess<TKey, TValue>
 */
interface RequestInterface extends \ArrayAccess {

  public function getMethod(): \App\Http\Enum\MethodEnum;


  public function getUri(): \Psr\Http\Message\UriInterface;


  /**
   * @return null|string|array<string, mixed>
   */
  public function getQuery(string $key): null|string|array;


  public function getCookie(string $name): ?string;

  
  public function getHeaderLine(string $header): string;
}