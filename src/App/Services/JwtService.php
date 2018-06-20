<?php


namespace App\Services;


use App\ReadModel\Interfaces\TicketFinderInterface;
use App\ReadModel\Interfaces\ApplicationFinderInterface;
use App\ReadModel\Interfaces\UserFinderInterface;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use PHPUnit\Runner\Exception;

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

    public function validateToken($token)
    {
        try {
            $tokenData = JWT::decode($token, $this->getSecretKey(null, null), ['HS256']);
        } catch (SignatureInvalidException $e) {
            // TODO log Alerts
            return false;
        } catch (ExpiredException $e) {
            return false;
        } catch (\UnexpectedValueException $e) {
            return false;
        } catch ( \Throwable $e ) {
            // all other errors
            return false;   
        }

        return $tokenData;
    }

    public function checkTokenPermissions(\stdClass $tokenData)
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