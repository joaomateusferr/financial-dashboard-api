<?php

namespace App\Constants;

class ServersConstants {

    private const CurrentCustomerServerID = 1;

    public static function getCurrentCustomerServerID() : string {

        return self::CurrentCustomerServerID;

    }

}