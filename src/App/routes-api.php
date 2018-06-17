<?php
/**
 * @var Slim\App $app
 */

function responseJsonError(\Psr\Http\Message\ResponseInterface $response, $statusCode = 200, $message = [])
{
    return $response->withStatus($statusCode)->withJson($message);
}

$app->group('/api/v1', function () {
    $this->get('/check', App\Api\Controllers\TicketController::class . ':check')->setName('auth.checkTicket');
});