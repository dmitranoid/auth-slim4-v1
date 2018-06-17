<?php
/**
 * User: shl
 * Date: 17.06.2018
 * Time: 0:00
 */

namespace App\ReadModel\PdoFinder\Application;


use App\ReadModel\Interfaces\ApplicationFinderInterface;
use App\Readmodel\PdoFinder\PdoGenericFinder;

class PdoApplicationFinder extends PdoGenericFinder implements ApplicationFinderInterface
{
    function byId($id)
    {
        $query = $this->fquery
            ->from('apps')
            ->where('id', $id);
        return $query->fetch();
    }

    function byCode($code)
    {
        $query = $this->fquery
            ->from('apps')
            ->where('code', $code);
        return $query->fetch();
    }

    function forUser($userId)
    {
        $query = $this->fquery
            ->from('apps')
            ->select('apps.*, user_app.status as user_app_status')
            ->leftJoin('user_app ON apps.id = user_app.app_id')
            ->where('user_id', $userId);
        return $query->fetchAll();
    }

}