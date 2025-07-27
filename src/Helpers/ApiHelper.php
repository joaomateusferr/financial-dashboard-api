<?php

namespace App\Helpers;

class ApiHelper {

    public static function generateToken() : string {

        return bin2hex(random_bytes(32));

    }

}