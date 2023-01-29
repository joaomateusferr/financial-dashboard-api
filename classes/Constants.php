<?php

class Constants {

    private const BRAPI_BASE_URL = 'https://brapi.dev/api/v2';

    private const B3_API_BASE_URL = 'https://sistemaswebb3-balcao.b3.com.br';

    private const INDICATOR_UPDATE_TIME = 300; //5 minutes

    public static function getProjectPath() {

        $ProjectPath = explode("/", $_SERVER['DOCUMENT_ROOT']);
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
    
}