<?php

namespace App\Helpers;

class SessionHelper {

    private const StandardDuration = 86400; //a day


    public static function generateToken() : string {

        return bin2hex(random_bytes(32));

    }

    public static function getStandardDuration() : int {

        return self::StandardDuration;

    }

}