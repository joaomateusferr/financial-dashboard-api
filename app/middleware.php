<?php

declare(strict_types=1);

use Slim\App;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\HttpNotFoundMiddleware;
use App\Middlewares\HttpMethodNotAllowedMiddleware;

return function (App $app) {

    $app->add(new HttpMethodNotAllowedMiddleware());
    $app->add(new HttpNotFoundMiddleware());
    $app->add(new AuthMiddleware());

};
