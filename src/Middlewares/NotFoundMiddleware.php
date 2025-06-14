<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Factory\ResponseFactory;
use App\Helpers\ResponseHelper;

class NotFoundMiddleware
{
    public function __invoke(Request $Request, $Handler): Response
    {
        try {

            return $Handler->handle($Request);

        } catch (HttpNotFoundException $Exception) {

            $ResponseFactory = new ResponseFactory();
            $Response = $ResponseFactory->createResponse(404);
            $Response->getBody()->write(ResponseHelper::format('not found', true));
            return $Response->withHeader('Content-Type', ResponseHelper::getDefaultContentType());
        }
    }
}