<?php


namespace App\Services;


use App\ReadModel\Interfaces\ApplicationFinderInterface;
use App\ReadModel\Interfaces\TicketFinderInterface;
use App\ReadModel\Interfaces\UserFinderInterface;

class TicketService
{
    const ticketSalt = 'bYfd5!b5-/F4s';
    /**
     * @var TicketFinderInterface
     */
    protected $tickerFinder;

    /**
     * @var UserFinderInterface
     */
    protected $userFinder;

    /**
     * @var ApplicationFinderInterface
     */
    protected $appFinder;

    public function __construct(
        TicketFinderInterface $ticketFinder,
        UserFinderInterface $userFinder,
        ApplicationFinderInterface $appFinder
    ) {
        $this->tickerFinder = $ticketFinder;
        $this->userFinder = $userFinder;
        $this->appFinder = $appFinder;
    }

    public function checkTicket($userId, $appId)
    {
        $this->tickerFinder->byUserApp($userId, $appId);
    }

    public function generateTicket($userId, $appId)
    {
        $user = $this->userFinder->byId($userId);
        $app = $this->appFinder->byId($appId);
        if ($user && $app) {
            return md5($user['name'] . $app['code'] . $this::ticketSalt);
        }
        return false;
    }
}