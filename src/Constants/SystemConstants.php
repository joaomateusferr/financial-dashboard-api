<?php

namespace App\Constants;

class SystemConstants {

    private const RequiredEnvironmentVariables = [

    ];

    public static function getRequiredEnvironmentVariables() : array {
        return self::RequiredEnvironmentVariables;
    }

}