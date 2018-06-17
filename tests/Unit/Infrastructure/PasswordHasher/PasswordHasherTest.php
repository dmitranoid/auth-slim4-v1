<?php
/**
 * User: shl
 * Date: 17.06.2018
 * Time: 0:27
 */

namespace Unit\Infrastructure\PasswordHasher;

use App\Infrastructure\PasswordHasher\PasswordHasher;
use PHPUnit\Framework\TestCase;

class PasswordHasherTest extends TestCase
{
    /**
     * @var PasswordHasher
     */
    private $hasher;

    protected function setUp()
    {
        $this->hasher = new PasswordHasher();
    }

    public function testCheckBCrypt()
    {
        $testPassword = '12345678+qwerty';
        $testHash = password_hash($testPassword, PASSWORD_BCRYPT);
        $this->assertEquals(true, $this->hasher->check($testPassword, $testHash));
    }

    public function testHashBCrypt()
    {
        $testPassword = '12345678+qwerty';
        $this->assertEquals(true, password_verify($testPassword, $this->hasher->hash($testPassword)));
    }
}
