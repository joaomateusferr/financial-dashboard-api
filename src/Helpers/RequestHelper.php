<?php

namespace App\Helpers;

class RequestHelper {

    public static function formatBody(string $Body) : array {

        if(empty($Body))
            return [];

        if(!json_validate($Body))
            return [];

        return json_decode($Body, true);

    }

}