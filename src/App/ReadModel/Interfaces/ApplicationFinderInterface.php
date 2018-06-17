<?php
/**
 * User: shl
 * Date: 16.06.2018
 * Time: 23:23
 */

namespace App\ReadModel\Interfaces;


interface ApplicationFinderInterface extends GenericFinderInterface
{
    function byId($id);

    function byCode($code);

    function forUser($userId);
}