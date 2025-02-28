<?php
declare(strict_types=1);
namespace App\Tests\Http\Session;

use App\Http\Session\Session;
use PHPUnit\Framework\TestCase;


class SessionTest extends TestCase {

  /**
   * @var Session
   */
  private Session $session;

  public function setUp(): void {
    $Container = \App\Factory\ContainerFactory::create(\App\Factory\ContainerFactory::MODE_PHPUNIT);
    $this->session = $Container->get(Session::class);
    $this->session->clear();
  }

  public function testGetSet(): void {
    $key = "foo";
    $value = "bar";

    $this->session->set($key, $value);
    $this->assertEquals($value, $this->session->get($key));
  }

  public function testGetSetTTL(): void {
    $key = "foo";
    $value = "bar";

    $this->session->set($key, $value, -1);
    $this->assertNull($this->session->get($key));
  }

  public function testRemoveValue(): void {
    $key = "foo";
    $value = "bar";

    $this->session->set($key, $value);
    $this->assertEquals($value, $this->session->get($key));
    $this->session->remove($key);
    $this->assertNull($this->session->get($key));
  }


  public function testGetSetOnNamespace(): void {
    $ns = $this->session->createNamespace("ns");

    $key = "foo";
    $value = 'bar';

    $ns->set($key, $value);
    $this->assertEquals($value, $ns->get($key));
    // původní session musí být prázdný
    $this->assertNull($this->session->get($key));
  }

  public function testRemoveOnNamespace(): void {
    $ns = $this->session->createNamespace("ns");

    $key = "foo";
    $value = 'bar';

    $ns->set($key, $value);
    $this->assertEquals($value, $ns->get($key));
    $ns->remove($key);
    $this->assertNull($ns->get($key));
  }
}
