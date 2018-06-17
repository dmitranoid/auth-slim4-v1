<?php
/**
 * User: shl
 * Date: 16.06.2018
 * Time: 23:42
 */

namespace App\Infrastructure\Interfaces\PasswordHasher;


interface PasswordHasherInterface
{
    function __construct(array $config = []);

    function check($password, $hash): bool;

    function hash($value): string;

}