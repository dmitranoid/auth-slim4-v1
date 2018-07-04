<?php
/**
 * @var Slim\App $app
 */

function responseJsonError(\Psr\Http\Message\ResponseInterface $response, $statusCode = 200, $message = [])
{
    return $response->withStatus($statusCode)->withJson($message);
}

$app->get('/checktoken', App\Api\Controllers\AuthController::class . ':checkToken')->setName('login.checkToken');
//
$app->get('/userinfo', App\Api\Controllers\UserController::class . ':getUserInfo')->setName('api.user.getinfo')
    ->add(new \App\Middlewares\JwtAuthMiddleware());