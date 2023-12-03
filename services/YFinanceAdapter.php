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
        'average_monthly_dividend'
    ];

    private const DEFAULT_TEMPLATE_FOLDER = '/tmp/dividend-map';

    private const DEFAULT_DIVIDEND_MAP_MAIN_PATH = '/dividend-map/src/main.py';

    private static function getDefaultCountryInformation() {
        return self::DEFAULT_COUNTRY_INFORMATION;
    }

    public static function getDefaultExportAssetColumns() {
        return self::DEFAULT_EXPORT_ASSET_COLUMNS;
    }

    public static function getDefaultTemplateFolder() {
        return self::DEFAULT_TEMPLATE_FOLDER;
    }

    public static function getDefaultDividendMapMainPath() {
        return self::DEFAULT_DIVIDEND_MAP_MAIN_PATH;
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