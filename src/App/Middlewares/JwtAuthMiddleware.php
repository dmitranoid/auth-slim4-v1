<?php

namespace App\Middlewares;

use App\ReadModel\PdoFinder\Application\PdoApplicationFinder;
use App\ReadModel\PdoFinder\Ticket\PdoTicketFinder;
use App\ReadModel\PdoFinder\User\PdoUserFinder;
use App\Services\JwtService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Слой аутентификации при обращении к API
 */
class JwtAuthMiddleware
{
    protected $container;

    /**
     * @var ApiAuthService
     */
    protected $authService;

    public function __construct() {
//        $this->container = $container;
    }

    /**
     * Get the bearer token from the request headers.
     *
     * @return string|null
     */
    protected function getBearerToken($authHeaderString)
    {

        if ('Bearer ' == substr($authHeaderString, 0, 7)) {
            return substr($authHeaderString, 7, strlen($authHeaderString)-7);
        }
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next) {

        $token = $request->getHeader('Authorisation');

        $db = new \PDO('');

        $jwtService = new JwtService(
            new PdoTicketFinder($db),
            new PdoUserFinder($db),
            new PdoApplicationFinder($db),
            getenv('SECRET_KEY')
        );

        $jwtData = $jwtService->validateToken($token);

        if (false === $jwtData) {
            return $response->withStatus(401)->withJson(['status'=>'error', 'errors'=>['code'=>'0', 'message'=> 'check token error']]);
        }

        $request = $request->withAttribute('jwtUserId', $jwtData['userId']??'')->withAttribute('jwtAppId', $jwtData['appId']??'' );

        return $next($request, $response);
    }
}