<?php


namespace App\Services;


use App\ReadModel\Interfaces\ApplicationFinderInterface;
use App\ReadModel\Interfaces\TicketFinderInterface;
use App\ReadModel\Interfaces\UserFinderInterface;
use Firebase\JWT\JWT;

class JwtService
{

    protected $secretKey;
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

    /**
     * Return secretKey
     * @param int $userId
     * @param int $appId $
     * @return string
     */

    protected function getSecretKey($userId, $appId)
    {
        return $this->secretKey; //getenv('AUTH_SECRET') ;
    }

    public function __construct(
        TicketFinderInterface $ticketFinder,
        UserFinderInterface $userFinder,
        ApplicationFinderInterface $appFinder,
        $secretKey
    ) {
        $this->tickerFinder = $ticketFinder;
        $this->userFinder = $userFinder;
        $this->appFinder = $appFinder;
        $this->secretKey = $secretKey;
    }

    public function checkTicket($userId, $appId)
    {
        $this->tickerFinder->byUserApp($userId, $appId);
    }

    public function generateToken($userId, $appId)
    {
        $user = $this->userFinder->byId($userId);
        $app = $this->appFinder->byId($appId);
        assert($user && $app, 'user and app must exists for token generation');
        $jwtPayloadData = [
            'userId'=>$user['id'],
            'appId'=>$app['id'],
            'iss'=>getenv('SITE_URL'),
            'iat'=> (new \DateTime())->getTimestamp(),
            'exp'=> (new \DateTime('+12 hour'))->getTimestamp(),

        ];
        $jwtToken = JWT::encode($jwtPayloadData, $this->getSecretKey($userId, $appId));

        return $jwtToken;
    }
}