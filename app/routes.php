<?php

declare(strict_types=1);


use Slim\App;
use App\Controllers\PublicController;
use App\Controllers\UserController;

return function (App $app) {

    $app->get('/ping', [PublicController::class, 'ping']);
    $app->post('/user/create', [UserController::class, 'create']);
    $app->post('/user/login', [UserController::class, 'login']);

};
