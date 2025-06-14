<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

// Register auth middleware
$middleware = require __DIR__ . '/../app/middleware.php';
$middleware($app);

// Register routes
$routes = require __DIR__ . '/../app/routes.php';
$routes($app);

$app->run();
