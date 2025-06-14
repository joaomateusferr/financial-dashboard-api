<?php

declare(strict_types=1);

use Slim\App;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\NotFoundMiddleware;

return function (App $app) {

    $app->add(new NotFoundMiddleware());
    $app->add(new AuthMiddleware());

};
