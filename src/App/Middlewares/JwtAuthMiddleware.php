<?php

namespace App\Middlewares;

use App\ReadModel\PdoFinder\Ticket\PdoTicketFinder;
use App\ReadModel\PdoFinder\Application\PdoApplicationFinder;
use App\ReadModel\PdoFinder\User\PdoUserFinder;
use App\Services\JwtService;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Слой аутентификации при обращении к API
 *
 */
class JwtAuthMiddleware
{
    protected $container;

    public function __construct()
    {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        $bearerHeader = $request->getHeader('Authorization')[0] ?? '';
        $token = $this->getBearerToken($bearerHeader);
        $db = new \PDO('sqlite::memory:'); // при вызове validateToken finders не используются (пока)

        $jwtService = new JwtService(
            new PdoTicketFinder($db),
            new PdoUserFinder($db),
            new PdoApplicationFinder($db),
            getenv('AUTH_SECRET')
        );

        $jwtData = $jwtService->validateToken($token);
        if (false === $jwtData) {
            return $response->withStatus(401)->withJson(['status' => 'error', 'errors' => ['code' => '0', 'message' => 'check token error']]);
        }

        // если проверка токена прошла успешно, добавляем атрибуты
        // authUserId, 'authAppId' к запросу
        $request = $request->withAttribute('authUserId', $jwtData->userId ?? '')->withAttribute('authAppId', $jwtData->appId ?? '');
        return $next($request, $response);
    }

    /**
     * Получить bearer токен из заголовка Authorisation запроса
     *
     * @var string
     * @return string|null
     */
    protected function getBearerToken($authHeaderString)
    {
        if ('Bearer ' == substr($authHeaderString, 0, 7)) {
            return substr($authHeaderString, 7, strlen($authHeaderString) - 7);
        }
    }
}