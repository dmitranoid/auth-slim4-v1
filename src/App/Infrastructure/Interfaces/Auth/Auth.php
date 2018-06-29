<?php
/**
 * Created by PhpStorm.
 * User: svt3
 * Date: 29.06.2018
 * Time: 11:34
 */

namespace App\Infrastructure\Interfaces\Auth;


use App\Infrastructure\Interfaces\Session\SessionInterface;

interface Auth
{
    function login($username, $password);
    function logout();
    function isLoggedIn():bool;
}