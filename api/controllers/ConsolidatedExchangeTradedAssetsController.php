<?php

class ConsolidatedExchangeTradedAssetsController {

    public static function getPositions(array $Request) : array {

        //validadte costumer id from auth here

        try{

            $Dividends = isset($Request['Parameters']['Dividends']) && $Request['Parameters']['Dividends'] ? true : false;

            $Customer = new Customer($Request['Arguments']['CustomerID']);

            $Positions = $Customer->getPositions($Dividends);
            $ExchangeTradedAssets = $Customer->getExchangeTradedAssets();

            $Response = [];

            foreach($Positions as $Key => $Position){

                if($Dividends)
                    $Infos = empty($Position['monetary_return_with_dividends']) ? ['monetary' => $Position['monetary_return'], 'percentage' => $Position['percentage_return']] : ['monetary' => $Position['monetary_return_with_dividends'], 'percentage' => $Position['percentage_return_with_dividends']] ;
                else
                    $Infos = ['monetary' => $Position['monetary_return'], 'percentage' => $Position['percentage_return']];

                //$Infos['currency_id'] = $ExchangeTradedAssets[$Key]['currency_id'];

                $Response[$ExchangeTradedAssets[$Key]['currency_id']][$ExchangeTradedAssets[$Key]['ticker']] = $Infos;

            }

            function monetaryReturnDescSort($Position1, $Position2) {
                if ($Position1['monetary'] == $Position2['monetary']) return 0;
                return ($Position1['monetary'] > $Position2['monetary']) ? -1 : 1;
            }

            //uasort($Response,"monetaryReturnDescSort");

           return $Response;

        } catch (Exception $Ex) {

            echo(var_export($Ex,1)."\n");

        }

    }

}