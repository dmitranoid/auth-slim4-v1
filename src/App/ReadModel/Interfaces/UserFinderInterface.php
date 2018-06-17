<?php
/**
 * User: shl
 * Date: 16.06.2018
 * Time: 17:03
 */

namespace App\ReadModel\Interfaces;


interface UserFinderInterface extends GenericFinderInterface
{
    function byId($id);

    function byName($name);

    function byIpAddress($ipAddress);

    function byNameAndPassword($name, $password);
}