<?php

class Indicators {
    
    private $IndicatorsFilePath;
    private $DollarRealExchangeRate;
    private $SpecialSettlementAndCustodySystem; //AKA SELIC
    private $LatestUpdate;

    function __construct() {

        $this->IndicatorsFilePath = Constants::getProjectPath().'/config/indicators.json';

        if(file_exists($this->IndicatorsFilePath)){
            
            $Indicators = json_decode(file_get_contents($this->IndicatorsFilePath), true);

            if(!isset($Indicators['LatestUpdate']) || $Indicators['LatestUpdate'] > time() - 300) { //if it's not set or the last update was more than 5 minutes ago

                $this->updateIndicators();

            } else {
                
                foreach ($Indicators as $Key => $Indicator){
                    $this->$Key = $Indicator;
                }
            
            }

        } else {

            $this->updateIndicators();

        }
    
    }

    function updateIndicators() {

        $this->IndicatorsFilePath = Constants::getProjectPath().'/config/indicators.json';
        $this->DollarRealExchangeRate = $this->getDollarRealExchangeRate();
        $this->SpecialSettlementAndCustodySystem = $this->getSpecialSettlementAndCustodySystem();
        $this->LatestUpdate = time();

        $Indicators = [];

        foreach($this as $Key => $Value){
            $Indicators[$Key] = $Value;
        }

        file_put_contents($this->IndicatorsFilePath, json_encode($Indicators, JSON_PRETTY_PRINT));
    
    }

    function getDollarRealExchangeRate() {
        
        $Curl = curl_init();

        curl_setopt_array($Curl, [
                CURLOPT_URL => Constants::getBrapiBaseUrl().'/currency?currency=USD-BRL',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ]
        );

        $Response = curl_exec($Curl);

        curl_close($Curl);

        if($Response === false){
            
            return false;
        
        } else {    //parse api response
            
            $Currency = (json_decode($Response, true))['currency'];
            
            if(isset($Currency[0]['askPrice']) && isset($Currency[0]['updatedAtTimestamp'])){
                
                $DollarRealExchangeRate['AskPrice'] = number_format($Currency[0]['askPrice'], 2);
                $DollarRealExchangeRate['LatestUpdate'] = intval($Currency[0]['updatedAtTimestamp']);
                
                return $DollarRealExchangeRate;

            } else {

                return false;

            }
        }
    
    }

    function getSpecialSettlementAndCustodySystem() {

        $Curl = curl_init();

        curl_setopt_array($Curl, [
                CURLOPT_URL => Constants::getB3ApiBaseUrl().'/featuresDIProxy/DICall/GetRateDI/eyJsYW5ndWFnZSI6InB0LWJyIn0=',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
            ]
        );

        $Response = curl_exec($Curl);

        curl_close($Curl);

        if($Response === false){
            
            return false;
        
        } else {

            $Selic = json_decode($Response, true);

            if(isset($Selic['rate']) && isset($Selic['date'])){

                $SpecialSettlementAndCustodySystem['Rate'] = number_format(str_replace(",",".",$Selic['rate']), 2);
                $SpecialSettlementAndCustodySystem['LatestUpdate'] = strtotime(str_replace("/","-",$Selic['date']));

                return $SpecialSettlementAndCustodySystem;

            } else {

                return false;

            }

        }
    
    }
    
}