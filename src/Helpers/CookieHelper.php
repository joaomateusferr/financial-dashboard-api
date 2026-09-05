<?php

namespace App\Helpers;

class CookieHelper {

    public static function getSID (array $ResponseHeaderLines) : ?string {

        $SID = null;

        foreach($ResponseHeaderLines as $ResponseHeaderLine){

            if (preg_match('/^Set-Cookie:\s*([^;]+)/i', $ResponseHeaderLine, $Matches)) {

                $SID = $Matches[1];
                break;

            }

        }

        return $SID;

    }

}