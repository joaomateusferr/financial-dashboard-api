<?php

namespace App\Helpers;

class APIRequestHelper {

    public static function sendRequest(string $Method, string $Endpoint, array $Content = [], array $Headers = []) : ?array {

        $Url = $_SERVER['API_BASE_URL'].'/api'.$Endpoint;

        $Curl = curl_init();
        curl_setopt($Curl, CURLOPT_URL, $Url);
        curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($Curl, CURLOPT_TIMEOUT, $_SERVER['UI_API_REQUEST_TIMEOUT']);
        curl_setopt($Curl, CURLOPT_CUSTOMREQUEST, $Method);

        if(!empty($Content))
            curl_setopt($Curl, CURLOPT_POSTFIELDS, json_encode($Content));

        if(!empty($Headers))
            curl_setopt($Curl, CURLOPT_HTTPHEADER, $Headers);

        $Response = curl_exec($Curl);

        if (curl_errno($Curl)) {

            $ErrorMessage = curl_error($Curl);
            $ErrorCode = curl_errno($Curl);

            echo "[cURL Error] code: $ErrorCode | Message: $ErrorMessage";

            if($Response === false)
                return null;

        } else {

            if(!json_validate($Response))
                return null;

            $HTTPCode = curl_getinfo($Curl, CURLINFO_HTTP_CODE);
            $Response = json_decode($Response, true);
            $Response['HTTPCode'] = $HTTPCode;

        }

        if(!empty($Response['destroy-session'])){

            if (session_status() === PHP_SESSION_ACTIVE) {
                $_SESSION = [];
                session_destroy();
            }

        }

        return $Response;

    }

}