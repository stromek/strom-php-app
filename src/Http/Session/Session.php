<?php
declare(strict_types=1);

namespace App\Http\Session;




use App\Http\Session\Storage\SessionStorageInterface;


class Session {

  const SESSION_NAME = "SESSID";

  const LIFE_TIME = 0;

  private \App\Api\Request\Request $request;

  private SessionNamespace $namespace;

  private Handler\SessionHandlerInterface $handler;

  private SessionStorageInterface $storage;

  private const KEY_EXPIRE = "e";

  private const KEY_VALUE = "v";

  public function __construct(\App\Api\Request\Request $Request, \App\Http\Session\Handler\SessionHandlerInterface $Handler,  SessionStorageInterface $Storage) {
    $this->request = $Request;
    $this->storage = $Storage;
    $this->namespace = new SessionNamespace($this, "");
    $this->handler = $Handler;
  }

  public function createNamespace(string $namespace): SessionNamespace {
    return $this->namespace->createNamespace($namespace);
  }

  /**
   * Získání surové hodnot ze session nezávisle na NS
   */
  public function getValue(string $key): mixed {
    self::init();

    $data = $this->storage->getValue($key);
    if(is_null($data) OR !is_array($data)) {
      return null;
    }

    $time = time();
    $isExpired = ($data[self::KEY_EXPIRE] ?? $time) < $time;

    if($isExpired) {
      $this->removeValue($key);
      return null;
    }

    return $data[self::KEY_VALUE];
  }


  /**
   * Zadan surové hodnot d session nezávisle na NS
   *
   * @param string $key
   * @param mixed $value
   * @param int $ttl 0 = neomezená platnost, jinak platí jak kladná tak záponá
   * @return void
   */
  public function setValue(string $key, mixed $value, int $ttl = 0): void {
    self::init();

    $this->storage->setValue($key, [
      self::KEY_EXPIRE => ($ttl !== 0) ? (time() + $ttl) : null,
      self::KEY_VALUE => $value
    ]);
  }

  /**
   * Odstraneni hodnoty ze session nezávisle na NS
   * @param string $key
   * @return void
   */
  public function removeValue(string $key): void {
    $this->storage->removeValue($key);
  }


  public function get(string $name): mixed {
    return $this->namespace->get($name);
  }


  public function set(string $name, mixed $value, int $ttl = 0): void {
    $this->namespace->set($name, $value, $ttl);
  }


  public function remove(string $name): void {
    $this->namespace->remove($name);
  }



  /**
   * Smazání všech dat
   */
  public function clear(): void {
    $this->storage->clear();
  }


  public static function isActive(): bool {
    return session_status() == PHP_SESSION_ACTIVE;
  }

  /**
   * Vypnuté konfigurací
   */
  public static function isDisabled(): bool {
    return session_status() == PHP_SESSION_DISABLED;
  }


  private function init(): void {
    if(self::isActive()) {
      return;
    }

    if(self::isDisabled()) {
      throw new \RuntimeException("Session is disabled by configuration.");
    }

    $this->validateSessionID($this->request->getCookie(self::SESSION_NAME));
    $this->handler->register();

    session_name(self::SESSION_NAME);

    \session_set_cookie_params(['lifetime' => self::LIFE_TIME, 'path' => '/', 'secure' => false, 'httponly' => true, 'samesite' => "Lax"]);

    session_start();
  }

  /**
   * @throws \App\Http\HttpException
   */
  private function validateSessionID(?string $cookieSession_id): void {
    if(session_id() OR !$cookieSession_id) {
      return;
    }

    $idLength = intval(ini_get("session.sid_length"));

    if(preg_match("/^[a-zA-Z0-9]{".$idLength."}$/", $cookieSession_id)) {
      return;
    }

    $id = session_create_id();
    if(!$id) {
      throw new \App\Http\HttpException("Failed to create session ID.");
    }

    session_id($id);
  }

}