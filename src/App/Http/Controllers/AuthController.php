<?php

namespace App\Http\Controllers;

use App\Helpers\NetworkHelper;
use App\Infrastructure\PasswordHasher\PasswordHasher;
use App\ReadModel\PdoFinder\Application\PdoApplicationFinder;
use App\ReadModel\PdoFinder\Ticket\PdoTicketFinder;
use App\ReadModel\PdoFinder\User\PdoUserFinder;
use App\Services\AuthService;
use App\Services\TicketService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AuthController extends GenericController
{

    public function loginShow(
        ServerRequestInterface $request,
        ResponseInterface $response,
        Array $args = []
    ): ResponseInterface {
        $query = $request->getQueryParams();
        $userFinder = new PdoUserFinder($this->db);
        $user = isset($query['username']) ? false : $userFinder->byIpAddress(NetworkHelper::getClientIp($request->getServerParams()));
        $viewData = [
            'content' => '',
            'application' => $query['application'] ?? '',
            'username' => $query['username'] ?? $user['name'] ?? '',
            'password' => '',
        ];
        return $this->view->render($response, 'front/auth/show.twig', $viewData);
    }

    public function loginPost(
        ServerRequestInterface $request,
        ResponseInterface $response,
        Array $args = []
    ): ResponseInterface {
        $query = $request->getParams();
        $userFinder = new PdoUserFinder($this->db);
        $appFinder = new PdoApplicationFinder($this->db);
        $authService = new AuthService(
            $userFinder,
            $appFinder,
            new PasswordHasher()
        );
        // check user with given password exists
        if (false === ($user = $userFinder->byName($query['username'] ?? '')
                && $authService->checkUserLogin($query['username'] ?? '', $query['password'] ?? '')
            )) {
            $this->logger->alert('login error',
                ['username' => $query['username'], 'application' => $query['application']]);
            $this->flashMessages->addMessage('error', 'неверное имя пользователя или пароль');
            return $response->withRedirect(NetworkHelper::path_for_route('front.login.show', [], $query));
        }
        // check app permissions
        if (false == $authService->checkUserPermissionForApp($query['username'], $query['application'] ?? '')) {
            $this->logger->alert('application is not allowed for user',
                ['username' => $query['username'], 'application' => $query['application']]);
            $this->flashMessages->addMessage('error', 'нет разрешения на использование приложения');
            return $response->withRedirect(NetworkHelper::path_for_route('front.login.show', [], $query));
        }
        // create ticket
        $ticketService = new TicketService(
            new PdoTicketFinder($this->db),
            $userFinder,
            $appFinder
        );
        $ticket = $ticketService->generateTicket($user->id, $application->id);
        // TODO need send username and password to client app
        return $response->withRedirect($ticketService->generateRedirectUrl($app->url . $ticket->getTicket()));
    }

}