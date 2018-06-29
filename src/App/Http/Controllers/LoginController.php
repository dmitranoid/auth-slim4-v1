<?php

namespace App\Http\Controllers;

use App\Helpers\NetworkHelper;
use App\Infrastructure\Auth\Auth;
use App\Infrastructure\PasswordHasher\PasswordHasher;
use App\ReadModel\PdoFinder\Application\PdoApplicationFinder;
use App\ReadModel\PdoFinder\Ticket\PdoTicketFinder;
use App\ReadModel\PdoFinder\User\PdoUserFinder;
use App\Services\JwtService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LoginController extends GenericController
{

    public function show(
        ServerRequestInterface $request,
        ResponseInterface $response,
        Array $args = []
    ): ResponseInterface {
        $query = $request->getQueryParams();
        $userFinder = new PdoUserFinder($this->db);
        $appFinder = new PdoApplicationFinder($this->db);
        $user = isset($query['username']) ? $userFinder->byIpAddress(NetworkHelper::getClientIp($request->getServerParams())) : false;
        $app = isset($query['app']) ? $appFinder->byCode($query['app']) : false;
        if (!$app) {
            // входим прямо на сайт
            // $this->flashMessages->addMessage('error', 'Неправильно переданные данные, обратитесь к администратору');
        }
        $viewData = [
            'content' => 'Вход в систему',
            'appId' => $app['id'],
            'title' => $app['name'],
            'username' => $query['username'] ?? $user['name'] ?? '',
        ];
        return $this->view->render($response, 'front/login/show.twig', $viewData);
    }

    public function post(
        ServerRequestInterface $request,
        ResponseInterface $response,
        Array $args = []
    ): ResponseInterface {
        $query = $request->getParams();
        $userFinder = new PdoUserFinder($this->db);
        $appFinder = new PdoApplicationFinder($this->db);
        $auth = new Auth(
            $userFinder,
            $appFinder,
            new PasswordHasher(),
            $this->session
        );
        // есть ли пользователь с введенными данными
        if (false === ($user = $userFinder->byName($query['username'] ?? '')
                && $auth->login($query['username'] ?? '', $query['password'] ?? '')
            )) {
            $this->logger->alert('login error',
                ['username' => $query['username'], 'application' => $query['application']]);
            $this->flashMessages->addMessage('error', 'неверное имя пользователя или пароль');
            return $response->withRedirect(NetworkHelper::path_for_route('front.login.show', [], $query));
        }
        // есть ли у пользователя права на приложение
        if (false == $auth->checkUserPermissionForApp($query['username'], $query['application'] ?? '')) {
            $this->logger->alert('application is not allowed for user',
                ['username' => $query['username'], 'application' => $query['application']]);
            $this->flashMessages->addMessage('error', 'нет разрешения на использование приложения');
            return $response->withRedirect(NetworkHelper::path_for_route('front.login.show', [], $query));
        }
        // выдаем ключ
        $jwtService = new JwtService(
            new PdoTicketFinder($this->db),
            $userFinder,
            $appFinder,
            getenv('AUTH_SECRET')
        );
        $jwtAccessToken = $jwtService->generateToken($user->id, $app->id);
        // TODO need send username and password to client app
        return $response->withRedirect($jwtService->generateRedirectUrl($app->url, $jwtAccessToken));
    }

}