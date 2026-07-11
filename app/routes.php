<?php

declare(strict_types=1);

use Slim\App;
use App\Controllers\PublicController;
use App\Controllers\UserController;
use App\Controllers\SessionController;
use App\Controllers\ExchangeTradedAssetsController;
use App\Controllers\AssetQualificationController;
use App\Controllers\AssetTypeController;
use App\Controllers\ExchangeController;
use App\Middlewares\AuthMiddleware;

return function (App $app) {

    $app->get('/api/ping', [PublicController::class, 'ping']);
    $app->get('/api/limits', [PublicController::class, 'getApiLimits']);
    $app->post('/api/user', [UserController::class, 'create']);
    $app->post('/api/session', [SessionController::class, 'set']);
    $app->delete('/api/session', [SessionController::class, 'delete'])->add(new AuthMiddleware());
    $app->post('/api/common-information/exchange-traded-assets', [ExchangeTradedAssetsController::class, 'create'])->add(new AuthMiddleware());
    $app->get('/api/common-information/exchange-traded-assets', [ExchangeTradedAssetsController::class, 'get']);
    $app->get('/api/common-information/asset-qualification', [AssetQualificationController::class, 'get']);
    $app->get('/api/common-information/asset-type', [AssetTypeController::class, 'get']);
    $app->get('/api/common-information/exchange', [ExchangeController::class, 'get']);

};
