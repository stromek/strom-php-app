<?php
declare(strict_types=1);
namespace App\Util;



use App\Entity\Entity;


/**
 * @implements \Iterator<string|int, mixed>
 * @implements \ArrayAccess<string|int, mixed>
 */
class Arr implements \ArrayAccess, \Iterator, \Serializable, \Countable {

  /**
   * @var array<array-key, mixed>
   */
  private array $array;

  /**
   * @param array<array-key, mixed> $array
   */
  public function __construct(array $array) {
    $this->array = $array;
  }

  /**
   * @template T of Entity
   * @param  array<array-key, mixed>|\Iterator|T $array
   */
  public static function create(array|\Iterator|Entity $array): Arr {
    $data = match(true) {
      is_array($array) => $array,
      $array instanceof Entity => $array->toArray(),
      $array instanceof \Iterator => self::iteratorToArray($array)
    };

    return new self($data);
  }

  /**
   * @return array<array-key, mixed>
   */
  private static function iteratorToArray(\Iterator $Iterator): array {
    $data = [];
    foreach($Iterator as $key => $item) {
      $data[$key] = $item;
    }
    return $data;
  }

  /**
   * https://www.php.net/manual/en/function.explode.php
   * @param string $separator
   * @param string $string
   * @param int $limit
   * @return Arr
   */
  public static function explode(string $separator, string $string, int $limit = PHP_INT_MAX): Arr {
    if(empty($separator)) {
      throw new \InvalidArgumentException("Separator cannot be empty.");
    }
    return new self(explode($separator, $string, $limit));
  }

  /**
   * https://www.php.net/manual/en/function.implode.php
   * @param string $separator
   * @return string
   */
  public function implode(string $separator = ""): string {
    return implode($separator, $this->array);
  }

  public static function split(string $pattern, string $subject, int $limit = -1, int $flags = 0): Arr {
    error_clear_last();
    $parts = preg_split($pattern, $subject, $limit, $flags);
    if($error = error_get_last()) {
      throw new \InvalidArgumentException($error['message']);
    }
    if(!is_array($parts)) {
      throw new \InvalidArgumentException("Cannot create array, preg_split failed.");
    }

    return new self($parts);
  }

  public function first(): mixed {
    return array_slice($this->array, 0, 1)[0] ?? null;
  }

  public function last(): mixed {
    return array_slice($this->array, -1, 1)[0] ?? null;
  }

  public function every(callable $callback): bool {
    foreach($this->array as $index => $item) {
      if(!$callback($item, $index, $this->array)) {
        return false;
      }
    }
    return true;
  }

  public function some(callable $callback): bool {
    foreach($this->array as $index => $item) {
      if($callback($item, $index, $this->array)) {
        return true;
      }
    }
    return false;
  }

  /**
   * @link https://www.php.net/manual/en/function.array-filter.php
   * @param callable|null $callback
   * @param int $mode
   * @return Arr
   */
  public function filter(callable $callback = null, int $mode = 0): Arr {
    return new self(array_filter($this->array, $callback, $mode));
  }


  /**
   * Filtrování associativniho pole na zaklade hodnoty dle klice
   *
   * <code>
   *   $array = [
   *     ["group" => 1, "name" => "name1"],
   *     ["group" => 2, "name" => "name2"],
   *     ["group" => 2, "name" => "name3"],
   *     ["group" => 3, "name" => "name4"],
   *   ];
   *
   *   $result = Arr::create($array)->filterAssocValue("group", 2);
   *   var_dump($result); // [1 => ["group" => 2, "name" => "name2"], 2 => ["group" => 2, "name" => "name3"]]
   * </code>
   *
   * @param string|int $key
   * @param mixed $value
   * @return Arr
   */
  public function filterAssocValue(string|int $key, mixed $value): Arr {
    return $this->filter(function(array|\ArrayAccess $array) use ($key, $value): bool {
      return is_callable($value, true) ? $value($array[$key], $array) : ($array[$key] === $value);
    });
  }


  /**
   * @link https://www.php.net/manual/en/function.array-sum.php
   * @return int|float
   */
  public function sum(): int|float {
    return array_sum($this->array);
  }


  public function slice(int $offset, ?int $length = null, bool $preserve_keys = false): Arr {
    return new self(array_slice($this->array, $offset, $length, $preserve_keys));
  }


  /**
   * @link https://www.php.net/manual/en/function.array-reduce.php
   * @param callable $callable
   * @param mixed|null $initial
   * @return mixed
   */
  public function reduce(callable $callable, mixed $initial = null): mixed {
    return array_reduce($this->array, $callable, $initial);
  }

  /**
   * array_reduce ale vystup musi byt pole
   *
   * @param callable $callable
   * @param mixed[] $initial
   * @return Arr
   */
  public function areduce(callable $callable, array $initial = []): Arr {
    return new self(array_reduce($this->array, $callable, $initial));
  }

  /**
   * @link https://www.php.net/manual/en/function.array-unique.php
   * @param int $flags
   * @return Arr
   */
  public function unique(int $flags = SORT_STRING): Arr {
    return new self(array_unique($this->array, $flags));
  }

  /**
   * @link https://www.php.net/manual/en/function.array-map.php
   * @param callable $callable
   * @return Arr
   */
  public function map(callable $callable): Arr {
    return new self(array_map($callable, $this->array));
  }

  public function mapWithKeys(callable $callable): Arr {
    return new self(array_map($callable, $this->array, array_keys($this->array)));
  }

  /**
   * @link https://www.php.net/manual/en/function.array-keys.php
   * @param mixed|null $search_value
   * @param bool $strict
   * @return Arr
   */
  public function keys(mixed $search_value = null, bool $strict = false): Arr {
    if(is_null($search_value)) {
      return new self(array_keys($this->array));
    }

    return new self(array_keys($this->array, $search_value, $strict));
  }

  /**
   * @link https://www.php.net/manual/en/function.array-values.php
   * @return Arr
   */
  public function values(): Arr {
    return new self(array_values($this->array));
  }


  /**
   * @link https://www.php.net/manual/en/function.array-merge.php
   * @param mixed[] ...$arrays
   * @return Arr
   */
  public function merge(array ...$arrays): Arr {
    return new self(array_merge($this->array, ...$arrays));
  }


  /**
   * <code>
   *  Arr::create([1,2], [3,4])->selfMerge([5,6])->toArray(); // [1,2,3,4,5,6]
   * </code>
   *
   * @param mixed[] ...$arrays
   * @return Arr
   */
  public function selfMerge(array ...$arrays): Arr {
    return new self(array_merge(...$this->toArray(), ...$arrays));
  }


  /**
   * Vyzobání pouze daných klíčú z pole
   *
   * <code>
   * $array = ["a" => 1, "b" => 2, "c" => 3];
   * var_dump(Arr::create($array)->cherryPickKey(["b", "c"]);  // ["b" => 2, "c" => 3]
   * </code>
   *
   * @param string[] $keys
   * @return Arr
   */
  public function cherryPickKey(array $keys): Arr {
    $keyMap = array_flip($keys);

    return $this->filter(function($key) use($keyMap): bool {
      return isset($keyMap[$key]);
    }, ARRAY_FILTER_USE_KEY);
  }

  /**
   * @link https://www.php.net/manual/en/function.array-column.php
   * @param int|string|null $columnKey
   * @param int|string|null $indexKey
   * @return Arr
   */
  public function column(int|string|null $columnKey, int|string|null $indexKey = null): Arr  {
    return new self(array_column($this->array, $columnKey, $indexKey));
  }


  /**
   * Nalezeni prvku dle callbacku
   *
   * @param callable $callback ($value, $key, $array)
   * @param bool $isFound
   * @return mixed
   */
  public function find(callable $callback, bool &$isFound = false): mixed {
    foreach ($this->array as $key => $value) {
      if($callback($value, $key, $this->array) === true) {
        $isFound = true;
        return $value;
      }
    }
    $isFound = false;
    return null;
  }


  /**
   * @link https://www.php.net/manual/en/function.usort.php
   * @param callable $callback
   * @return Arr
   */
  public function usort(callable $callback): Arr {
    $tmp = $this->array;
    usort($tmp, $callback);
    return new self($tmp);
  }


  /**
   * @link https://www.php.net/manual/en/function.sort.php
   * @param int $flags The optional second parameter sort_flags may be used to modify the sorting behavior using these values. Sorting type flags: SORT_REGULAR - compare items normally (don't change types)
   * @return Arr
   */
  public function sort(int $flags = SORT_REGULAR): Arr {
    $tmp = $this->array;
    sort($tmp, $flags);
    return new self($tmp);
  }


  /**
   * @link https://www.php.net/manual/en/function.asort.php
   * @param int $flags The optional second parameter sort_flags may be used to modify the sorting behavior using these values. Sorting type flags: SORT_REGULAR - compare items normally (don't change types)
   * @return Arr
   */
  public function asort(int $flags = SORT_REGULAR): Arr {
    $tmp = $this->array;
    asort($tmp, $flags);
    return new self($tmp);
  }


  /**
   * Doplneni hodnot dle callbacku. Klic je hodnota z aktualniho pole
   *
   * <code>
   * $array = [1, 2, 5, 10];
   * var_dump(Arr::create($array)->combineValue(fn(int $number): int => $number * $number));  // [1 => 1, 2 => 4, 5 => 25, 10 => 100]
   * </code>
   *
   * @param callable $callback
   * @return Arr
   */
  public function combineValue(callable $callback): Arr {
    $data = [];
    foreach($this->array as $value) {
      $data[$value] = $callback($value);
    }

    return new self($data);
  }


  /**
   * Doplneni klicu dle callbacku. Hodnota je hodnota z aktualniho pole
   *
   * <code>
   * $array = [1, 2, 5, 10];
   * var_dump(Arr::create($array)->combineKey(fn(int $number): int => $number * $number));  // [1 => 1, 4 => 2, 25 => 5, 100 => 10]
   * </code>
   *
   * @param callable $callback
   * @return Arr
   */
  public function combineKey(callable $callback): Arr {
    $data = [];
    foreach($this->array as $value) {
      $data[$callback($value)] = $value;
    }

    return new self($data);
  }


  /**
   * Zavolání funkce na seznam hodnot a vracení výsledku této fce
   */
  public function finish(callable $callback): mixed {
    return $callback($this);
  }


  /**
   * @return mixed[]
   */
  public function toArray(): array {
    return $this->array;
  }

  /**
   * ArrayAccess interface
   */

  public function offsetExists(mixed $offset): bool {
    return isset($this->array[$offset]);
  }

  public function offsetGet(mixed $offset): mixed {
    return $this->array[$offset];
  }

  public function offsetSet(mixed $offset, mixed $value): void {
    if (is_null($offset)) {
      $this->array[] = $value;
    } else {
      $this->array[$offset] = $value;
    }
  }

  public function offsetUnset(mixed $offset): void {
    unset($this->array[$offset]);
  }

  /**
   * Iterator interface
   */

  public function current(): mixed {
    return current($this->array);
  }


  public function next(): void {
    next($this->array);
  }

  public function key(): string|int|null {
    return key($this->array);
  }


  public function valid(): bool {
    return current($this->array) !== false;
  }


  public function rewind(): void {
    reset($this->array);
  }

  /**
   * Serializable (php 8.0)
   */

  public function serialize(): string {
    return serialize($this->array);
  }


  public function unserialize(string $data): void {
    $this->array = unserialize($data);
  }

  /**
   * Serializable (php 8.0, 8.1)
   */
  public function __serialize(): array {
    return $this->array;
  }

  /**
   * @param mixed[] $data
   */
  public function __unserialize(array $data): void {
    $this->array = $data;
  }


  /**
   * Countable
   */

  public function count(): int {
    return count($this->array);
  }

  /**
   * Magic methods
   */
  public function __set(string $name, mixed $value): void {
    $this->array[$name] = $value;
  }

  public function __get(string $name): mixed {
    return $this->array[$name];
  }

  public function __debugInfo(): array {
    return $this->array;
  }

  /**
   * @return array<array-key, mixed>
   */
  public function __invoke(): array {
    return $this->array;
  }

}