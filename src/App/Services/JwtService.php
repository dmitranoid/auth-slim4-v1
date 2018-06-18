<?php


namespace App\Services;


use App\ReadModel\Interfaces\ApplicationFinderInterface;
use App\ReadModel\Interfaces\TicketFinderInterface;
use App\ReadModel\Interfaces\UserFinderInterface;

class JwtService
{
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

    public function generateToken($userId, $appId)
    {
        $user = $this->userFinder->byId($userId);
        $app = $this->appFinder->byId($appId);
        if ($user && $app) {
            return md5($user['name'] . $app['code'] );
        }
        return false;
    }
}