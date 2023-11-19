<?php

class Credentials {

    private static $Credentials = [
        'User' => 'admin', 'Password' => '1401',
    ];

    public static function get(){

        return self::$Credentials;

    }

    public static function getSSLCertificatePath(){

        return 'path/to/.pem';

    }

}