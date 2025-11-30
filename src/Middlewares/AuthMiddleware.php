<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Slim\Psr7\Factory\ResponseFactory;
use App\Helpers\ResponseHelper;
use App\Helpers\RouteHelper;

class AuthMiddleware {

    public function __invoke(Request $Request, Handler $Handler): Response {

        if(in_array(RouteHelper::getRouteRoot($Request->getUri()->getPath()), RouteHelper::getNoAuthRoots()))
            return $Handler->handle($Request);

        $Auth = $Request->getHeaderLine('Authorization');

        if (!$Auth) {

            $ResponseFactory = new ResponseFactory();
            $Response = $ResponseFactory->createResponse(401);
            $Response->getBody()->write(ResponseHelper::format('Unauthorized', true));
            return $Response->withHeader('Content-Type', ResponseHelper::getDefaultContentType());

        }

        return $Handler->handle($Request);

    }
}
