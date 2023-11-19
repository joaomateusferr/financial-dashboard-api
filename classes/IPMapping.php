<?php

class IPMapping {

    private static $ServerList = [
        'customers' => ['IP' => '192.168.15.78', 'Port' => 3306, 'HasSSL' => false],
        'common-information' => ['IP' => '192.168.15.78', 'Port' => 3306, 'HasSSL' => false],
        'customers-server-1' => ['IP' => '192.168.15.78', 'Port' => 3306, 'HasSSL' => false],
    ];

    public static function get(string $Server){

        if(isset(self::$ServerList[$Server]))
            return self::$ServerList[$Server];

        return [];

    }

}