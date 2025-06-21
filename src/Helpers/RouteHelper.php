<?php

namespace App\Helpers;

class RouteHelper {

    private const NoAuthRoots = [
        'ping',
        'login'
    ];

    public static function getNoAuthRoots() : array {

        return self::NoAuthRoots;

    }

    public static function getRouteRoot(string $Route) : string {

        return explode('/', $Route, 2)[1];

    }

}