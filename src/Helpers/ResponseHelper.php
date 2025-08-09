<?php

namespace App\Helpers;

class ResponseHelper {

    private const DefaultContentType = 'application/json';

    public static function getDefaultContentType() : string {
        return self::DefaultContentType;
    }

    public static function format(array|string $Payload, bool $Error = false) : string {

        if($Error)
            return json_encode(['error' => true, 'message' => $Payload]);
        else
            return json_encode(['result' => $Payload]);

    }

}