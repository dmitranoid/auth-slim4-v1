<?php

namespace App\ReadModel\PdoFinder\User;

use App\ReadModel\Interfaces\UserFinderInterface;
use App\Readmodel\PdoFinder\PdoGenericFinder;

class PdoUserFinder extends PdoGenericFinder implements UserFinderInterface
{

    function byId($id)
    {
        $query = $this->fquery
            ->from('user')
            ->where('id', $id);
        return $query->fetch();
    }

    function byName($name)
    {
        $query = $this->fquery
            ->from('users')
            ->where('name', $name)
            ->limit(1);
        return $query->fetch();
    }

    function byIpAddress($ipAddress)
    {
        $data = [
            'id' => 9999999,
            'name' => 'dummyuser',
            'pass' => '123',
            'status' => 'active'
        ];
        /*
        $data = $this->fquery
            ->from('user')
            ->where('ip', $ipAddress)
            ->limit(1);
        */
        return $data;
    }

    function byNameAndPassword($username, $password)
    {
        $data = $this->fquery
            ->from('users')
            ->where('name', $username)
            ->and('password', $password)
            ->limit(1);
        return $data;
    }
}