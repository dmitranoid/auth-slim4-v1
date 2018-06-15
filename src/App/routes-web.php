<?php
// Routes
/*
$app->get('/', App\Actions\HomeAction::class)
    ->setName('homepage');
*/
$app->get('/login', App\Http\Controllers\AuthController::class . ':loginShow')->setName('front.login');

