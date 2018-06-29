<?php
/**
 * Created by PhpStorm.
 * User: svt3
 * Date: 22.06.2018
 * Time: 11:16
 */

namespace App\Infrastructure\Interfaces\Session;


interface SessionInterface
{
    function get($key, $default = null);
    function set($key, $value);
    function exist($key):bool;
    function remove($key);
}