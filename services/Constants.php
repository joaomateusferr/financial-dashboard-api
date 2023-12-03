<?php

class Constants {

    private const BRAPI_BASE_URL = 'https://brapi.dev/api/v2';

    private const B3_API_BASE_URL = 'https://sistemaswebb3-balcao.b3.com.br';

    private const INDICATOR_UPDATE_TIME = 300; //5 minutes

    private const DIVIDEND_IMPORT_CSV_TEMPLATE = [
        'Date', //YYYY/MM/DD
        'Ticker',
        'NumberOfShares', //0.00 -> double
        'AmountPaid', //0.00 -> double
        'Taxes', //0.00 -> double
        'NetAmountPaid', //0.00 -> double
        'ShareValue', //0.00 -> double
        'Type', //1-18 -> int or null
        'FinancialInstitution' //string
    ];

    public static function getProjectPath() {

        $ProjectPath = explode("/", $_SERVER['DOCUMENT_ROOT']);
	    unset($ProjectPath[array_key_last($ProjectPath)]);
	    $ProjectPath = implode("/", $ProjectPath);

        if(!empty($ProjectPath))
            return $ProjectPath;

        $ProjectPath = explode("/", dirname(__FILE__));
        unset($ProjectPath[array_key_last($ProjectPath)]);
        $ProjectPath = implode("/", $ProjectPath);
        return $ProjectPath;

    }

    public static function getBrapiBaseUrl() {

        return self::BRAPI_BASE_URL;

    }

    public static function getB3ApiBaseUrl() {

        return self::B3_API_BASE_URL;

    }

    public static function getIndicatorUpdateTime() {

        return self::INDICATOR_UPDATE_TIME;

    }

    public static function getDividendImportCSVTemplate() {

        return self::DIVIDEND_IMPORT_CSV_TEMPLATE;

    }

}