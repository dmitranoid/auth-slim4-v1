<?php
/**
 * Created by PhpStorm.
 * User: svt3
 * Date: 21.06.2018
 * Time: 13:48
 */

namespace App\Middlewares;


use App\Helpers\NetworkHelper;
use App\Infrastructure\Auth\Auth;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\RouterInterface;

class SessionAuthMiddleware
{

    protected $auth;

    protected $router;

    public function __construct(RouterInterface $router, Auth $auth)
    {
        $this->auth = $auth;
        $this->router = $router;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        if (!$this->auth->isLoggedIn()) {
            $redirectBackUrl =['redirect_url' => $request->getUri()->getPath()];
            return $response->withRedirect(NetworkHelper::path_for_route('front.login.show',[], $redirectBackUrl));
        }
        $next($request, $response);
    }
}