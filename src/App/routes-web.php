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
$app->get('/login', App\Http\Controllers\AuthController::class . ':loginShow')->setName('front.login.show');
$app->post('/login', App\Http\Controllers\AuthController::class . ':loginPost')->setName('front.login.post');

$app->get('/testdb',
    function (\Psr\Http\Message\RequestInterface $req, \Psr\Http\Message\ResponseInterface $resp, $args) use ($app) {

        $db = new Envms\FluentPDO\Query($app->getContainer()->get('db'));
        $query = $db->from('users')
            ->where('id', 1)
            ->limit(1);

        print_r($query->fetch());
        echo("<br>==============<br>");
        foreach ($query as $row) {
            print_r($row);
            print_r("<br>");
        }
        return $resp;
    });

