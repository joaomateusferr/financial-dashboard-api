<?php

namespace App\UiControllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\UiBase;

class DashboardController extends UiBase {

    public function dashboard(Request $Request, Response $Response) {

        return self::buildResponse($Response, 'dashboard-main.php');

    }

}