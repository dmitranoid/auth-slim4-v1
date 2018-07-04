<?php

namespace App\Api\Controllers;


use App\Api\ApiResourceGenericController;
use App\ReadModel\PdoFinder\User\PdoUserFinder;
use App\Services\UserService;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class UserController
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var \PDO
     */
    protected $db;

    public function __construct(LoggerInterface $logger, \PDO $db)
    {
        $this->logger = $logger;
        $this->db = $db;
    }
    public function getUserInfo(RequestInterface $request, ResponseInterface $response, array $args)
    {
        $query = $request->getQueryParams();
        $authUserId = $request->getAttribute('authUserId');
        $userService = new UserService(
            new PdoUserFinder($this->db)
        );

        $user = $userService->finder()->byId($authUserId);

        $user = array_intersect_key($user, ['id' => '', 'name' => '', 'status' => '', 'comment' => '']);

        $data = [
            'result' => 'success',
            'data' => $user,
        ];
        return $response->withStatus(200)->withJson($data);
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