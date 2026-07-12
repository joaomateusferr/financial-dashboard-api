<?php

namespace App\UiControllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\UiBase;

class HomeController extends UiBase {

    public function signin(Request $Request, Response $Response) {

        return self::buildResponse($Response, 'signin.php');

    }

    public function home(Request $Request, Response $Response) {

        return self::buildResponse($Response, 'home.php');

    }

    public function login(Request $Request, Response $Response) {

        return self::buildResponse($Response, 'login.php');

    }

    public function resetPassword(Request $Request, Response $Response) {

        return self::buildResponse($Response, 'reset-password.php');

    }

}