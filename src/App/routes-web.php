<?php
// Routes
/*
$app->get('/', App\Actions\HomeAction::class)
    ->setName('homepage');
*/
$app->get('/', function (
    \Psr\Http\Message\ServerRequestInterface $request,
    \Psr\Http\Message\ResponseInterface $response,
    $args
) use ($app) {
    return $response->withRedirect($app->getRouter()->pathFor('front.login.show'));
})->setName('front.home');

$app->get('/login', App\Http\Controllers\LoginController::class . ':show')->setName('front.login.show');
$app->post('/login', App\Http\Controllers\LoginController::class . ':post')->setName('front.login.post');
$app->get('/logout', App\Http\Controllers\LoginController::class . ':logout')->setName('front.logout');


$app->get('/homepage', App\Http\Controllers\HomeController::class . ':show')->setName('front.homepage')
    ->add(new \App\Middlewares\SessionAuthMiddleware(
        $app->getRouter(),
        $app->getContainer()->get(\App\Infrastructure\Interfaces\Auth\AuthInterface::class)
    ));
