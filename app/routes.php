<?php

declare(strict_types=1);


use Slim\App;
use App\Controllers\HelloController;
use App\Controllers\PublicController;

return function (App $app) {

    $app->get('/ping', [PublicController::class, 'ping']);
    $app->get('/login', [PublicController::class, 'login']);

    $app->get('/hello/{name}', [HelloController::class, 'greetName']);


};
