<?php

namespace App\Api\Controllers;


use App\Api\ApiResourceGenericController;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class UserController extends ApiResourceGenericController
{
    protected $allowedActions = [
      'show',
    ];

    public function show(RequestInterface $request, ResponseInterface $response, array $args)
    {

    }

    /**
     * Undocumented function
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return void
     */
    public function check(RequestInterface $request, ResponseInterface $response, array $args) 
    {
        $userService = new App\Services\UserService();
        
    }

    public function login(RequestInterface $request, ResponseInterface $response, array $args)
    {

    }
    
    public function logout(RequestInterface $request, ResponseInterface $response, array $args)
    {

    }
   


}