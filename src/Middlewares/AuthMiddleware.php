<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use App\Services\ApiBase;
use App\Helpers\RouteHelper;
use App\Repositories\SessionRepository;

class AuthMiddleware extends ApiBase {

    public function __invoke(Request $Request, Handler $Handler): Response {

        if(!RouteHelper::requireAuthentication($Request->getUri()->getPath(), $Request->getMethod()))
            return $Handler->handle($Request);

        if (empty($_COOKIE['sid']))
            return self::buildResponseFromFactory(['Unauthorized'], 401, true);

        $Session = SessionRepository::get($_COOKIE['sid'], ['ID', 'ExpiresAt']);

        if(empty($Session))
            return self::buildResponseFromFactory(['Unauthorized'], 401, true);

        if(time() > $Session['ExpiresAt']){

            SessionRepository::delete($_COOKIE['sid']);
            setcookie('sid', '', time() - 3600, '/', '', true, true);
            return self::buildResponseFromFactory(['Unauthorized'], 401, true);

        }

        return $Handler->handle($Request);

    }
}
