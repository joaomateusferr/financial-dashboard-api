<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\ApiBase;

class PublicController extends ApiBase {

    public function ping(Request $Request, Response $Response) {

        return self::buildResponse($Response,["pong"]);

    }

}