<?php

class Indicators {
    
    private $IndicatorsFilePath;
    public $DollarRealExchangeRate;
    public $SpecialSettlementAndCustodySystem; //AKA SELIC
    public $InterbankDepositRate;//AKA TAXA DI
    public $LatestUpdate;

    function __construct() {

        $this->IndicatorsFilePath = Constants::getProjectPath().'/config/indicators.json';

        if(file_exists($this->IndicatorsFilePath)){
            
            $Indicators = json_decode(file_get_contents($this->IndicatorsFilePath), true);

            if(!isset($Indicators['LatestUpdate']) || $Indicators['LatestUpdate'] < time() - Constants::getIndicatorUpdateTime()) {

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
        $this->InterbankDepositRate = $this->getInterbankDepositRate();
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

        if($Response === false){    //error
            
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

    function getInterbankDepositRate() {

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

        if($Response === false){    //error
            
            return false;
        
        } else {    //parse api response

            $DI = json_decode($Response, true);

            if(isset($DI['rate']) && isset($DI['date'])){

                $InterbankDepositRate['Rate'] = number_format(str_replace(",",".",$DI['rate']), 2);
                $InterbankDepositRate['LatestUpdate'] = strtotime(str_replace("/","-",$DI['date']));

                return $InterbankDepositRate;

            } else {

                return false;

            }

        }
    
    }

    function getSpecialSettlementAndCustodySystem() {

        $Curl = curl_init();

        $Date = date("d/m/Y");
        $Date = str_replace("/", "%2F", $Date);

        curl_setopt_array($Curl, [
                CURLOPT_URL => Constants::getBrapiBaseUrl()."/prime-rate?country=brazil&historical=true&start=$Date&end=$Date&sortBy=date&sortOrder=desc",
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

        if($Response === false){    //error
            
            return false;
        
        } else {    //parse api response

            $Selic = (json_decode($Response, true))['prime-rate'];

            if(isset($Selic[0]['value']) && isset($Selic[0]['date'])){

                $SpecialSettlementAndCustodySystem['Rate'] = number_format($Selic[0]['value'], 2);
                $SpecialSettlementAndCustodySystem['LatestUpdate'] = strtotime(str_replace("/","-",$Selic[0]['date']));

                return $SpecialSettlementAndCustodySystem;

            } else {

                return false;

            }

        }
    
    }
    
}