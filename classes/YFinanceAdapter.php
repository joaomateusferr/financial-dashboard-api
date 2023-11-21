<?php

class YFinanceAdapter {

    private const DEFAULT_COUNTRY_INFORMATION = [

        'united_states' => [

            'currency' => [
                'name' => 'Dollar',
                'symbol' => '$'
            ]

        ],

        'brazil' => [

            'currency' => [
                'name' => 'Real',
                'symbol' => 'R$'
            ]

        ]

    ];

    private const DEFAULT_EXPORT_ASSET_COLUMNS = [
        'ticker',
        'market_price',
        'payment_months',
        'average_annual_dividend',
        'average_monthly_dividend',
        'magic_number',
        'dividend_only_payback_period_in_months',
        'dividend_only_payback_period_in_years'
    ];

    private static function getDefaultCountryInformation() {
        return self::DEFAULT_COUNTRY_INFORMATION;
    }

    public static function getDefaultExportAssetColumns() {
        return self::DEFAULT_EXPORT_ASSET_COLUMNS;
    }

    public static function assembleTemplate() {

        $Template = self::getDefaultCountryInformation();

        foreach($Template as $Key => $CountryInformation){

            $Template[$Key]['asset_information'] = [];
            $Template[$Key]['export_asset_columns'] = self::getDefaultExportAssetColumns();

        }

        return $Template;

    }

}