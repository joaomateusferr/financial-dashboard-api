<?php

declare(strict_types=1);

use Slim\App;
use App\UiControllers\HomeController;
use App\UiControllers\OperationController;
use App\Controllers\PublicController;
use App\Controllers\UserController;
use App\Controllers\SessionController;
use App\Controllers\ExchangeTradedAssetsController;
use App\Controllers\AssetQualificationController;
use App\Controllers\AssetTypeController;
use App\Controllers\ExchangeController;
use App\Middlewares\AuthMiddleware;

return function (App $App) {

    //Home
    $App->get('/', [HomeController::class, 'home']);

    //UI
    $App->get('/signin', [HomeController::class, 'signin']);
    $App->get('/login', [HomeController::class, 'login']);
    $App->get('/reset-password', [HomeController::class, 'resetPassword']);

    //Operations
    $App->post('/signin/result', [OperationController::class, 'signin']);

    //API
    $App->get('/api/ping', [PublicController::class, 'ping']);
    $App->get('/api/limits', [PublicController::class, 'getApiLimits']);
    $App->post('/api/user', [UserController::class, 'create']);
    $App->post('/api/session', [SessionController::class, 'set']);
    $App->delete('/api/session', [SessionController::class, 'delete'])->add(new AuthMiddleware());
    $App->post('/api/common-information/exchange-traded-assets', [ExchangeTradedAssetsController::class, 'create'])->add(new AuthMiddleware());
    $App->get('/api/common-information/exchange-traded-assets', [ExchangeTradedAssetsController::class, 'get']);
    $App->get('/api/common-information/asset-qualification', [AssetQualificationController::class, 'get']);
    $App->get('/api/common-information/asset-type', [AssetTypeController::class, 'get']);
    $App->get('/api/common-information/exchange', [ExchangeController::class, 'get']);

};
