<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Psr7\Factory\ResponseFactory;
use App\Helpers\ResponseHelper;

class HttpMethodNotAllowedMiddleware {

    public function __invoke(Request $Request, $Handler): Response {

        try {

            return $Handler->handle($Request);

        } catch (HttpMethodNotAllowedException $Exception) {

            $ResponseFactory = new ResponseFactory();
            $Response = $ResponseFactory->createResponse(405);
            $Response->getBody()->write(ResponseHelper::format('method not allowed', true));
            return $Response->withHeader('Content-Type', ResponseHelper::getDefaultContentType());
        }
    }
}