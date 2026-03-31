<?php

namespace App\Constants;

class ApiLimitsConstants {

    private const ExchangeTradedAssetsPost = 25;

    public static function getExchangeTradedAssetsPost() : int {
        return self::ExchangeTradedAssetsPost;
    }

}