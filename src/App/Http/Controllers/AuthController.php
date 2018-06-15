<?php

namespace App\Http\Controllers;

use
    Psr\Http\Message\ServerRequestInterface,
    Psr\Http\Message\ResponseInterface;

class AuthController extends GenericController
{

    public function loginShow(ServerRequestInterface $request, ResponseInterface $response, Array $args = []):ResponseInterface
    {
        $viewData = [
            'content' => __CLASS__. ' ' . __METHOD__,
        ];
        return $this->view->render($response, 'front/auth/show.twig');
    }

}