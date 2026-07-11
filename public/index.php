<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$App = AppFactory::create();

$Middleware = require __DIR__ . '/../app/middleware.php';
$Middleware($App);

$Routes = require __DIR__ . '/../app/routes.php';
$Routes($App);

$App->run();
