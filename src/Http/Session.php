<?php
declare(strict_types=1);

namespace App\Http;


class Session {

  const SESSION_NAME = "SESSID";

  const LIFE_TIME = 0;

  private \App\Api\Request\Request $request;

  public function __construct(\App\Api\Request\Request $Request) {
    $this->request = $Request;
  }


  public function get(string $name): mixed {
    self::init();

    return $_SESSION[$name] ?? null;
  }


  public function set(string $name, mixed $value): void {
    self::init();

    $_SESSION[$name] = $value;
  }


  private function init(): void {
    // Již spuštěné
    if(session_status() == PHP_SESSION_ACTIVE) {
      return;
    }
    // Vypnuté konfigurací
    if(session_status() == PHP_SESSION_DISABLED) {
      throw new \RuntimeException("Session is disabled by configuration.");
    }

    $this->validateSessionID($this->request->getCookie(self::SESSION_NAME));

    session_name(self::SESSION_NAME);

    session_set_cookie_params([
      'lifetime' => self::LIFE_TIME,
      'path' => '/',
      'secure' => false,
      'httponly' => true,
      'samesite' => "Lax"
    ]);

    session_start();
  }


  /**
   * @throws HttpException
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