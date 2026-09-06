<?php

namespace App\Constants;

class DashboardTabsConstants {

    private const DashboardTabs = [
        'indicators' => 'Indicators',
        'details' => 'Details',
    ];

    public static function getDashboardTabs() : array {
        return self::DashboardTabs;
    }

}