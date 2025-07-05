<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Helpers\RequestHelper;
use App\Helpers\ResponseHelper;

class UserController {

    public function login(Request $Request, Response $Response) {

        $Data = RequestHelper::formatBody($Request->getBody()->getContents());

        $Response->getBody()->write(ResponseHelper::format($Data));
        return $Response->withHeader('Content-Type', ResponseHelper::getDefaultContentType())->withStatus(200);

    }

    public function create(Request $Request, Response $Response) {

        $Data = RequestHelper::formatBody($Request->getBody()->getContents());

        $Response->getBody()->write(ResponseHelper::format($Data));
        return $Response->withHeader('Content-Type', ResponseHelper::getDefaultContentType())->withStatus(200);

    }

}