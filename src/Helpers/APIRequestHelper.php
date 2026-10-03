<?php

namespace App\Helpers;

class APIRequestHelper {

    public static function sendRequest(string $UserAgent, string $Method, string $Endpoint, array $Content = [], array $Headers = []) : ?array {

        $Url = $_SERVER['API_BASE_URL'].'/api'.$Endpoint;

        $Curl = curl_init();
        curl_setopt($Curl, CURLOPT_URL, $Url);
        curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($Curl, CURLOPT_HEADER, true);
        curl_setopt($Curl, CURLOPT_TIMEOUT, $_SERVER['UI_API_REQUEST_TIMEOUT']);
        curl_setopt($Curl, CURLOPT_USERAGENT, $UserAgent);
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

            $HeaderSize = curl_getinfo($Curl, CURLINFO_HEADER_SIZE);
            $Headers = self::parseHeaders(substr($Response, 0, $HeaderSize));
            $Response = substr($Response, $HeaderSize);

            if(!json_validate($Response))
                return null;

            $HTTPCode = curl_getinfo($Curl, CURLINFO_HTTP_CODE);
            $Response = json_decode($Response, true);
            $Response['HTTPCode'] = $HTTPCode;
            $Response['Headers'] = $Headers;

        }

        if(!empty($Response['destroy-session'])){

            if (session_status() === PHP_SESSION_ACTIVE) {
                $_SESSION = [];
                session_destroy();
            }

        }

        return $Response;

    }

    private static function parseHeaders(string $RawHeaders) : array {

        $Headers = [];

        foreach (explode("\r\n", trim($RawHeaders)) as $Line) {

            if (strpos($Line, ':') !== false) {

                [$Name, $Value] = explode(':', $Line, 2);
                $Headers[trim($Name)] = trim($Value);

            }

        }

        return $Headers;

    }

    public static function parseSetCookie(string $SetCookie): array {

        $Result = [];

        foreach (explode(';', $SetCookie) as $Item) {
            $Item = trim($Item);

            if ($Item === '')
                continue;

            [$Key, $Value] = array_pad(explode('=', $Item, 2), 2, true);

            $Result[$Key] = $Value;

        }

        return $Result;

    }

}