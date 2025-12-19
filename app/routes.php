<?php

declare(strict_types=1);


use Slim\App;
use App\Controllers\PublicController;
use App\Controllers\UserController;
use App\Controllers\SessionController;

return function (App $app) {

    $app->get('/ping', [PublicController::class, 'ping']);
    $app->post('/user', [UserController::class, 'create']);
    $app->post('/session', [SessionController::class, 'set']);

};
