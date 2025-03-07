<?php
declare(strict_types=1);


if(!function_exists("array_find")) {
  /**
   * @template T
   * @param array<array-key, T> $array
   * @param callable $callback (mixed $value, scalar $key)
   * @return T|null
   * @throws Exception
   */
  function array_find(array $array, callable $callback): mixed {
    foreach($array as $key => $value) {
      $result = $callback($value, $key);

      if(!is_bool($result)) {
        throw new \Exception("Return value of callback must be boolean");
      }

      if($result === true) {
        return $value;
      }
    }

    return null;
  }
}