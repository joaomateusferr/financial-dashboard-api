<?php

namespace App\Constants;

class ApiLimitsConstants {

    private const ExchangeTradedAssetsPost = 2;

    public static function getExchangeTradedAssetsPost() : int {
        return self::ExchangeTradedAssetsPost;
    }

}