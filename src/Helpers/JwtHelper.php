<?php

namespace App\Helpers;

use App\Constants\KeysConstants;
use App\Services\SharedMemory;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use \Exception;
use \stdClass;

class JwtHelper {

    private static function getKey() : string { //openssl rand -base64 32

        $SharedMemory = new SharedMemory(KeysConstants::getJwtCredentials());
        $JwtCredentials = $SharedMemory->read();

        if(empty($JwtCredentials))
            throw new Exception('Unable to get jwt credentials');

        if(empty($JwtCredentials['Key']))
            throw new Exception('Unable to get jwt credentials - key');

        return $JwtCredentials['Key'];

    }

    private static function getAlgorithm() : string {
        return 'HS256';
    }

    private static function getKeyObject() : Key {
        return new Key(self::getKey(), self::getAlgorithm());
    }

    public static function create(string $UserID, string $Type) : ?string {


        $Payload = [
            'id' => $UserID,
            'type' => $Type,
            'iat' => time(),
        ];

        $Jwt = null;

        try {
            $Jwt = JWT::encode($Payload, self::getKey(), self::getAlgorithm());
        } catch (Exception $Exception) {}

        return $Jwt;

    }

    public static function parse(string $Jwt) : array {

        $Headers = $Decoded = null;
        $Result = [];

        try {
            $Headers = new stdClass();
            $Decoded = JWT::decode($Jwt, self::getKeyObject(), $Headers);
        } catch (Exception $Exception) {}

        if(empty($Decoded))
            return [];

        $Result = [
            'Headers' => (array) $Headers,
            'Data' => (array) $Decoded,
        ];

        return $Result;

    }

}