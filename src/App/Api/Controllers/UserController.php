<?php

namespace App\Api\Controllers;


use App\Api\ApiResourceGenericController;
use App\ReadModel\PdoFinder\User\PdoUserFinder;
use App\Services\UserService;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class UserController
{
    public function __construct(LoggerInterface $logger, \PDO $db)
    {
        $this->logger = $logger;
        $this->db = $db;
    }
    public function getUserInfo(RequestInterface $request, ResponseInterface $response, array $args)
    {
        $inputData = $request->getQueryParams();
        $userService = new UserService(
            new PdoUserFinder($this->db)
        );

        $user = $userService->finder()->byId($inputData['jwtUserId']);

        $user = array_intersect_key(['id', 'name', 'status', 'comment'], $user);

        $data = [
            'result' => 'success',
            'data' => $user,
        ];

        return $data;
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

    }

}