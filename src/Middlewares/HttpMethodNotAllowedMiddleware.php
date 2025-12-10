<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpMethodNotAllowedException;
use App\Services\ApiBase;

class HttpMethodNotAllowedMiddleware extends ApiBase {

    public function __invoke(Request $Request, $Handler): Response {

        try {

            return $Handler->handle($Request);

        } catch (HttpMethodNotAllowedException $Exception) {

            return self::buildResponseFromFactory(['Method Not Allowed'], 405, true);

        }
    }
}