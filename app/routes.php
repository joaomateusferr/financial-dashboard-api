<?php

declare(strict_types=1);

use Slim\App;
use App\Controllers\PublicController;
use App\Controllers\UserController;
use App\Controllers\SessionController;
use App\Controllers\ExchangeTradedAssetsController;
use App\Middlewares\AuthMiddleware;

return function (App $app) {

    $app->get('/ping', [PublicController::class, 'ping']);
    $app->post('/user', [UserController::class, 'create']);
    $app->post('/session', [SessionController::class, 'set']);
    $app->delete('/session', [SessionController::class, 'delete'])->add(new AuthMiddleware());
    $app->post('/common-information/exchange-traded-assets', [ExchangeTradedAssetsController::class, 'create'])->add(new AuthMiddleware());

};
