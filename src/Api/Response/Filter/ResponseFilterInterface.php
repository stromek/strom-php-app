<?php
declare(strict_types=1);

namespace App\Api\Response\Filter;


/**
 * Transformace odpovědi API do jiného formátu (JSON/XML..)
 */
interface ResponseFilterInterface {

  public function contentType(): ?string;

  public function transform(mixed $value): string;

  /**
   * @param array<string, string> $headers
   * @return array<string, string>
   */
  public function transformHeaders(array $headers = []): array;
}