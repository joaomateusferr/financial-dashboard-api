<?php

namespace App\Services;

use App\Constants\Investidor10Constants;
use App\Constants\TaxesConstants;
use App\Helpers\Investidor10WebParser;

class Investidor10 {

    public static function getAssetsData(array $Assets) : array {

        $AssetsData = [];
        $TimeLimit = strtotime("-1 year");

        $BaseUrl = Investidor10Constants::getBaseUrl();
        $AsetTypeMap = Investidor10Constants::getAsetTypeMap();
        $Taxes = TaxesConstants::getTaxesMap();

        foreach($Assets as $FullTicker => $Type){

            if(!isset($AsetTypeMap[$Type])) //ignore
                continue;

            $AssetUrl = $BaseUrl.'/'.$AsetTypeMap[$Type].'/'.strtolower($FullTicker).'/';

            $Investidor10Parser = new Investidor10WebParser($AssetUrl);

            $AssetsData[$FullTicker] = [];
            $AssetsData[$FullTicker]['AnnualPayment'] = 0;
            $AssetsData[$FullTicker]['NetAnnualPayment'] = 0;
            $AssetsData[$FullTicker]['MarketPrice'] = $Investidor10Parser->getMarketPrice();

            $DividendsHistory = $Investidor10Parser->getDividendsHistory();

            foreach($DividendsHistory as $Index => $Line){

                if($DividendsHistory[$Index]['PaymentDate'] >= $TimeLimit && $DividendsHistory[$Index]['PaymentDate'] <= time()){

                    $AssetsData[$FullTicker]['AnnualPayment'] = $AssetsData[$FullTicker]['AnnualPayment'] + $DividendsHistory[$Index]['Income'];

                    if(isset($Taxes[$Type][$DividendsHistory[$Index]['Type']])){
                        $AssetsData[$FullTicker]['NetAnnualPayment'] = $AssetsData[$FullTicker]['AnnualPayment'] * (1 - $Taxes[$Type][$DividendsHistory[$Index]['Type']]);
                    } else {
                        $AssetsData[$FullTicker]['NetAnnualPayment'] = $AssetsData[$FullTicker]['AnnualPayment'];
                    }

                }

            }

            $AssetsData[$FullTicker]['AnnualPayment'] = round($AssetsData[$FullTicker]['AnnualPayment'], 3);
            $AssetsData[$FullTicker]['NetAnnualPayment'] = round($AssetsData[$FullTicker]['NetAnnualPayment'], 3);

            sleep(rand(0, 1));

        }

        return $AssetsData;

    }

}