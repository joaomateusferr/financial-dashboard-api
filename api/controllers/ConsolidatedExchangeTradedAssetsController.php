<?php

class ConsolidatedExchangeTradedAssetsController {

    public static function getPositions(array $Request) : array {

        //validadte costumer id from auth here

        try{

            $Dividends = isset($Request['Parameters']['Dividends']) && $Request['Parameters']['Dividends'] ? true : false;
            $OrderBy = isset($Request['Parameters']['OrderBy']) && $Request['Parameters']['OrderBy'] ? $Request['Parameters']['OrderBy'] : null;
            $Sort = isset($Request['Parameters']['Sort']) && $Request['Parameters']['Sort'] ? $Request['Parameters']['Sort'] : null;

            if(!isset($Sort))
                $Sort = 'Desc';

            if(!in_array($Sort,['Desc', 'Asc']))
                RequestHelper::prepareResponse(400, ["ErrorMessage" => "Invalid Sort value use Desc or Asc!"]);

            if(!isset($OrderBy))
                $OrderBy = 'Monetary';

            if(!in_array($OrderBy,['Monetary', 'Percentage']))
                RequestHelper::prepareResponse(400, ["ErrorMessage" => "Invalid OrderBy value use Monetary or Percentage!"]);

            $SortSring = strtolower($OrderBy).'Return'.$Sort.'Sort';
            
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

            function monetaryReturnAscSort($Position1, $Position2) {
                if ($Position1['monetary'] == $Position2['monetary']) return 0;
                return ($Position1['monetary'] < $Position2['monetary']) ? -1 : 1;
            }

            function percentageReturnDescSort($Position1, $Position2) {
                if ($Position1['percentage'] == $Position2['percentage']) return 0;
                return ($Position1['percentage'] > $Position2['percentage']) ? -1 : 1;
            }

            function percentageReturnAscSort($Position1, $Position2) {
                if ($Position1['percentage'] == $Position2['percentage']) return 0;
                return ($Position1['percentage'] < $Position2['percentage']) ? -1 : 1;
            }

            foreach($Response as $IsoCode => $Currency){

                uasort($Response[$IsoCode]['Assets'], $SortSring);
                $Response[$IsoCode]['CurrencysDetails'] = $CurrencysDetails[$IsoCode];
            }

           return $Response;

        } catch (Exception $Ex) {

            echo(var_export($Ex,1)."\n");

        }

    }

}