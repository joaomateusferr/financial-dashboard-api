<?php

namespace App\Constants;

class KeysConstants {

    private const DatabaseUserCredentials = 'db-credentials';
    private const ServersList = 'servers-list';

    public static function getDatabaseUserCredentials() : string {
        return self::DatabaseUserCredentials;
    }

    public static function getServersList() : string {
        return self::ServersList;
    }

}