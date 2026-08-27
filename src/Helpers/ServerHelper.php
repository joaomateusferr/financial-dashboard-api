<?php

namespace App\Helpers;

class ServerHelper {


    private static function convertStringToEnvironmentVariable(string $ServerName) : string {
        return strtoupper(str_replace("-","_",$ServerName));
    }

    public static function getDatabaseInfo(string $ServerName) : array {

        $EnvironmentVariable = self::convertStringToEnvironmentVariable($ServerName).'_DB';

        return [
            'Host' => $_SERVER[$EnvironmentVariable.'_HOST'],
            'Port' => $_SERVER[$EnvironmentVariable.'_PORT']
        ];

    }

    public static function getDatabaseCredentials() : array {

        return [
            'User' => $_SERVER['DB_USER'],
            'Password' => $_SERVER['DB_PASSWORD']
        ];

    }

}