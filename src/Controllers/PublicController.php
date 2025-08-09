<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Helpers\RequestHelper;
use App\Helpers\ResponseHelper;

class PublicController {

    public function ping(Request $Request, Response $Response) {

        $Data = ["pong"];

        $Response->getBody()->write(ResponseHelper::format($Data));
        return $Response->withHeader('Content-Type', ResponseHelper::getDefaultContentType())->withStatus(200);

    }

    public function login(Request $Request, Response $Response) {

        $Data = RequestHelper::formatBody($Request->getBody()->getContents());

        $Response->getBody()->write(ResponseHelper::format($Data));
        return $Response->withHeader('Content-Type', ResponseHelper::getDefaultContentType())->withStatus(200);

    }

}