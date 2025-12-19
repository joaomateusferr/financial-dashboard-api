<?php

namespace App\Helpers;

class RouteHelper {

    private const NoAuth = [
        'ping' => ['GET'],
        'user'=>['POST'],
        'session' => ['POST'],
    ];

    private static function getNoAuth() : array {

        return self::NoAuth;

    }

    public static function getRouteRoot(string $Route) : string {

        return explode('/', $Route)[1];

    }

    public static function requireAuthentication(string $Route, string $Method) : bool {

        $RouteRoot = self::getRouteRoot($Route);
        $NoAuth = self::getNoAuth();

        if(!isset($NoAuth[$RouteRoot]))
            return true;

        if(in_array($Method, $NoAuth[$RouteRoot]))
            return false;

        return true;

    }

}