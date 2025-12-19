<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use App\Services\ApiBase;
use App\Helpers\RouteHelper;

class AuthMiddleware extends ApiBase {

    public function __invoke(Request $Request, Handler $Handler): Response {

        if(!RouteHelper::requireAuthentication($Request->getUri()->getPath(), $Request->getMethod()))
            return $Handler->handle($Request);

        $Auth = $Request->getHeaderLine('Authorization');

        if (!$Auth) {

            return self::buildResponseFromFactory(['Unauthorized'], 401, true);

        }

        return $Handler->handle($Request);

    }
}
