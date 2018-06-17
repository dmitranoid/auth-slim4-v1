<?php
/**
 * User: shl
 * Date: 17.06.2018
 * Time: 22:10
 */

namespace App\ReadModel\PdoFinder\Ticket;


use App\ReadModel\Interfaces\TicketFinderInterface;
use App\Readmodel\PdoFinder\PdoGenericFinder;

class PdoTicketFinder extends PdoGenericFinder implements TicketFinderInterface
{
    function byUserApp($userId, $appId)
    {
        $query = $this->fquery
            ->from('tickets')
            ->where('user_id = :userId and app_id = :appId', [':userId' => $userId, ':appId' => $appId])
            ->limit(1);
        return $query->fetch();
    }

    function byTicketNo($ticketNo)
    {
        $query = $this->fquery
            ->from('tickets')
            ->where('ticket', $ticketNo)
            ->limit(1);
        return $query->fetch();
    }

}