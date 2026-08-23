<?php

use Slim\Factory\AppFactory;

require dirname(__DIR__, 1).'/config.php';

$App = AppFactory::create();

$Middleware = require __DIR__ . '/../app/middleware.php';
$Middleware($App);

$Routes = require __DIR__ . '/../app/routes.php';
$Routes($App);

$App->run();
