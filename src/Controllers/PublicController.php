<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Helpers\RequestHelper;
class PublicController extends BaseController {

    public function ping(Request $Request, Response $Response) {

        return self::buildResponse($Response,["pong"]);

    }

}