<?php

class ConsolidatedExchangeTradedAssetsController {

    public static function getPositions(array $Request) : array {

        //validadte costumer id from auth here

        try{

            $Dividends = isset($Request['Parameters']['Dividends']) && $Request['Parameters']['Dividends'] ? true : false;

            $Customer = new Customer($Request['Arguments']['CustomerID']);

            $Positions = $Customer->getPositions($Dividends);
            $ExchangeTradedAssets = $Customer->getExchangeTradedAssets();
            $Currencys = $Customer->getCurrencys();

            $Response = [];

            foreach($Positions as $Key => $Position){

                if($Dividends)
                    $Infos = empty($Position['monetary_return_with_dividends']) ? ['monetary' => $Position['monetary_return'], 'percentage' => $Position['percentage_return']] : ['monetary' => $Position['monetary_return_with_dividends'], 'percentage' => $Position['percentage_return_with_dividends']] ;
                else
                    $Infos = ['monetary' => $Position['monetary_return'], 'percentage' => $Position['percentage_return']];


                $Response[$Currencys[$ExchangeTradedAssets[$Key]['currency_id']]['iso_code']]['Assets'][$ExchangeTradedAssets[$Key]['ticker']] = $Infos;

            }


            foreach($Currencys as $Currency){

                $Infos = $Currency;
                unset($Infos['iso_code']);

                $CurrencysDetails[$Currency['iso_code']] = $Infos;
            }

            function monetaryReturnDescSort($Position1, $Position2) {
                if ($Position1['monetary'] == $Position2['monetary']) return 0;
                return ($Position1['monetary'] > $Position2['monetary']) ? -1 : 1;
            }

            foreach($Response as $IsoCode => $Currency){

                uasort($Response[$IsoCode]['Assets'],"monetaryReturnDescSort");
                $Response[$IsoCode]['CurrencysDetails'] = $CurrencysDetails[$IsoCode];
            }

           return $Response;

        } catch (Exception $Ex) {

            echo(var_export($Ex,1)."\n");

        }

    }

}