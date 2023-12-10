<?php

class ConsolidatedExchangeTradedAssetsController {

    public static function getPositions(array $Request) : array {

        //validadte costumer id from auth here

        try{

            if(isset($Request['Parameters']['Dividends']) && $Request['Parameters']['Dividends'])
                $Dividends = true;
            else
                $Dividends = false;

            $Customer = new Customer($Request['Arguments']['CustomerID']);

            $Positions = $Customer->getPositions($Dividends);
            $ExchangeTradedAssets = $Customer->getExchangeTradedAssets();

            $Response = [];

            foreach($Positions as $Key => $Position){

                if(isset($Positions[$Key]))
                    $Response[$ExchangeTradedAssets[$Key]['ticker']] = $Position;

            }

           return $Response;

        } catch (Exception $Ex) {

            echo(var_export($Ex,1)."\n");

        }

    }

}