<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use App\Services\ApiBase;

class HttpNotFoundMiddleware extends ApiBase {

    public function __invoke(Request $Request, $Handler): Response {

        try {

            return $Handler->handle($Request);

        } catch (HttpNotFoundException $Exception) {

            return self::buildResponseFromFactory(['Not Found'], 404, true);

        }
    }
}