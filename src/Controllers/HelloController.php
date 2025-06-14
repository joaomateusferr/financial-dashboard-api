<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Helpers\ResponseHelper;

class HelloController {

    public function greet(Request $Request, Response $Response) {

        $Data = ["Hello, from Controller!"];

        $Response->getBody()->write(ResponseHelper::format($Data));
        return $Response->withHeader('Content-Type', ResponseHelper::getDefaultContentType())->withStatus(200);

    }

    public function greetName(Request $Request, Response $Response, $Args) {

        $Name = !empty($Args['name']) ? $Args['name'] : 'user';
        $Data = ["Hello $Name, from Controller!"];

        $Response->getBody()->write(ResponseHelper::format($Data));
        return $Response->withHeader('Content-Type', ResponseHelper::getDefaultContentType())->withStatus(200);

    }

}