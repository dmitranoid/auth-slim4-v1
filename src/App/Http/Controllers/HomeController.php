<?php
/**
 * Created by PhpStorm.
 * User: svt3
 * Date: 28.03.2018
 * Time: 7:51
 */

namespace App\Http\Controllers;


use App\Helpers\NetworkHelper;
use App\Infrastructure\Auth\Auth;
use App\Infrastructure\Interfaces\Auth\AuthInterface;
use App\Infrastructure\PasswordHasher\PasswordHasher;
use App\Infrastructure\Interfaces\View\ViewInterface;
use App\ReadModel\PdoFinder\Application\PdoApplicationFinder;
use App\ReadModel\PdoFinder\User\PdoUserFinder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class HomeController extends GenericController
{
    public function show(ServerRequestInterface $request, ResponseInterface $response, Array $args)
    {
        $query = $request->getQueryParams();
        $userFinder = new PdoUserFinder($this->db);
        $appFinder = new PdoApplicationFinder($this->db);
        $auth = new Auth(
            $userFinder,
            $appFinder,
            new PasswordHasher(),
            $this->session
        );
        $user = $auth->getCurrentUser();

        $data = [
            'title' => 'Подключенные приложения',
            'items' => $appFinder->forUser($user['id']),
        ];
        return $this->view->render($response, 'front/homepage/show.twig', $data);
    }


    public function store(ServerRequestInterface $request, ResponseInterface $response, Array $args)
    {

    }


}