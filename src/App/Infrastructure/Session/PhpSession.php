<?php
/**
 * Created by PhpStorm.
 * User: svt3
 * Date: 29.06.2018
 * Time: 12:23
 */

namespace App\Infrastructure\Session;


use App\Infrastructure\Interfaces\Session\SessionInterface;

class PhpSession implements SessionInterface
{
    function get($key, $default = null)
    {
        if ($this->exist($key)) {
            return $_SESSION[$key];
        }
        return $default;
    }

    function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    function exist($key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    function remove($key)
    {
        unset($_SESSION[$key]);
    }

}