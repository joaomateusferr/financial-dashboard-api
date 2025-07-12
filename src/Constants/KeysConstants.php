<?php

namespace App\Constants;

class KeysConstants {

    private const DatabaseCredentials = 'db-credentials';
    private const ServersList = 'servers-list';

    public static function getDatabaseCredentials() : string {
        return self::DatabaseCredentials;
    }

    public static function getServersList() : string {
        return self::ServersList;
    }

}