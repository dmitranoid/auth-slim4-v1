<?php
/**
 * @var Slim\App $app
 */

function responseJsonError(\Psr\Http\Message\ResponseInterface $response, $statusCode = 200, $message = [])
{
    return $response->withStatus($statusCode)->withJson($message);
}

$app->group('/', function () {
    $this->get('checktoken', App\Api\Controllers\AuthController::class . ':checkToken')->setName('login.checkToken');
});
//
$app->group('/', function() {
    $this->get('userinfo', App\Api\Controllers\UserController::class. ':getInfo')->setName('api.user.getinfo');
})->add(new \App\Middlewares\JwtAuthMiddleware());