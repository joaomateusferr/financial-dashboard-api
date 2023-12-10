<?php

class AuthenticationMidware {

    private static function getAuthorization() {

        $Authorization = '';

        if (function_exists('apache_request_headers')) {

            $RequestHeaders = apache_request_headers();

            if (isset($RequestHeaders['Authorization']))
                $Authorization = trim($RequestHeaders['Authorization']);

        } elseif (isset($_SERVER['Authorization'])) {
            $Authorization = trim($_SERVER["Authorization"]);
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $Authorization = trim($_SERVER["HTTP_AUTHORIZATION"]);
        }

        return $Authorization;
    }

    private static function getBearerToken() {

        $Authorization = self::getAuthorization();

        if (!empty($Authorization)) {

            $Matches = [];

            if (preg_match('/Bearer\s(\S+)/', $Authorization, $Matches))
                return $Matches[1];

        }

        return false;
    }

    public static function validateApiToken() {

        $BearerToken = self::getBearerToken();

        if(empty($BearerToken) || $BearerToken != 'API_TOKEN')  //temporary validation
            RequestHelper::prepareResponse(401);
    }

    public static function validateCustomerToken(array $Request) {

    }

}