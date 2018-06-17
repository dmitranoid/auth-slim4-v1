<?php
/**
 * User: shl
 * Date: 17.06.2018
 * Time: 21:35
 */

namespace App\ReadModel\Interfaces;


interface TicketFinderInterface
{
    function __construct(\PDO $db);

    function byUserApp($userId, $appId);

    function byTicketNo($ticketNo);
}