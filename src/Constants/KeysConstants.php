<?php

namespace App\Constants;

class KeysConstants {

    private const JwtCredentials = 'jwt-credentials';
    private const DatabaseCredentials = 'db-credentials';
    private const ServersList = 'servers-list';

    public static function getJwtCredentials() : string {
        return self::JwtCredentials;
    }

    public static function getDatabaseCredentials() : string {
        return self::DatabaseCredentials;
    }

    public static function getServersList() : string {
        return self::ServersList;
    }

}