<?php
declare(strict_types=1);
namespace App\Util;


class Str {

  private const RANDOM_LIMIT = 10000;

  /**
   * @param int $length
   * @param string|string[]|null $allowedChars
   * @return string
   */
  public static function random(int $length, string|array $allowedChars = null): string {
    $string = "";

    $pattern = null;
    $allowedChars = is_string($allowedChars) ? str_split($allowedChars) : $allowedChars;
    if(!is_null($allowedChars)) {
      $pattern = '~[^'.(implode("|", array_map("preg_quote", $allowedChars))).']~';
    }

    $i = 0;
    do {
      $part = base_convert(bin2hex(openssl_random_pseudo_bytes(min($length * 2, 50))), 16, 36);

      if(!is_null($pattern)) {
        $part = preg_replace($pattern, "", $part);
      }

      $string .= $part;
      $i++;

      if($i > self::RANDOM_LIMIT) {
        break;
      }

    }while(strlen($string) < $length);

    return mb_substr($string, 0, $length);
  }


  public static function XORCipher(string $string, string $key): string {
    $res = "";
    for($i=0; $i<strlen($string); $i++)     {
      $res .= chr(ord($string[$i]) ^ ord($key[$i % strlen($key)]));
    }

    return $res;
  }


}
