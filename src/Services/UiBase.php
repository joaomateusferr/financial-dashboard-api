<?php

namespace App\Services;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer;

abstract class UiBase {

    protected static function buildResponse(Response $Response, string $View, array $Data = []) : Response {

        $Renderer = new PhpRenderer(dirname(__DIR__, 2).'/views');
        $Renderer->setLayout('base.php');
        return $Renderer->render($Response, $View, $Data);

    }

}