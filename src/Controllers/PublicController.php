<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;
use App\Services\ApiBase;
use App\Constants\ApiLimitsConstants;

class PublicController extends ApiBase {

    public function ui(Request $Request, Response $Response) {

        $Renderer = new PhpRenderer(dirname(__DIR__, 2).'/views');
        return $Renderer->render($Response, "page.php", ['Link' => '/?a=1', 'Site' => 'google']);

    }

    public function ping(Request $Request, Response $Response) {

        return self::buildResponse($Response,["pong"]);

    }

    public function getApiLimits(Request $Request, Response $Response) {

        $Result = [
            'common-information-exchange-traded-assets-post' => ApiLimitsConstants::getExchangeTradedAssetsPost(),
        ];

        return self::buildResponse($Response,$Result);

    }

}