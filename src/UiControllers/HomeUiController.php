<?php

namespace App\UiControllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\UiBase;

class HomeUiController extends UiBase {

    public function home(Request $Request, Response $Response) {

        return self::buildResponse($Response, 'home.php');

    }

}