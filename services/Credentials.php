<?php

class Credentials {

    public static function getCredentialsFromOptions() : array {

        $Options = new Options();
        return ['Credentials' => $Options->Credentials, 'SSLCertificatePath' => $Options->SSLCertificatePath];

    }

    public static function get() : array {

        return self::getCredentialsFromOptions()['Credentials'];

    }

    public static function getSSLCertificatePath(){

        return self::getCredentialsFromOptions()['SSLCertificatePath'];

    }

}