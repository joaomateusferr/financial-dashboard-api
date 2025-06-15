<?php

declare(strict_types=1);

use Slim\App;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\NotFoundMiddleware;
use App\Middlewares\HttpMethodNotAllowedMiddleware;

return function (App $app) {

    $app->add(new HttpMethodNotAllowedMiddleware());
    $app->add(new NotFoundMiddleware());
    $app->add(new AuthMiddleware());

};
