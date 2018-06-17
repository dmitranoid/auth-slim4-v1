<?php
/**
 * User: shl
 * Date: 16.06.2018
 * Time: 23:42
 */

namespace App\Infrastructure\PasswordHasher;


use App\Infrastructure\Interfaces\PasswordHasher\PasswordHasherInterface;

class PasswordHasher implements PasswordHasherInterface
{
    protected $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    function check($password, $hash): bool
    {
        return (password_verify($password, $hash));
    }

    function hash($password): string
    {
        $algo = $this->config['algo'] ?? PASSWORD_BCRYPT;
        return password_hash($password, $algo);
    }

}