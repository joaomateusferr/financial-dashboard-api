<?php

require_once dirname(__FILE__)."/../settings/configuration_file.php";

$Template = YFinanceAdapter::assembleTemplate();

try{

    $CommonInformationConnection = new MariaDB('common-information', 'common_information');

    $Sql = 'SELECT exchange_traded_assets.yfinance_ticker, exchange_traded_assets.dividend_frequency, currencys.symbol as currency_symbol, currencys.name as currency_name FROM exchange_traded_assets INNER JOIN currencys ON currencys.id = exchange_traded_assets.currency_id';
    $Stmt = $CommonInformationConnection->prepare($Sql);
    $Result = $Stmt->execute();

    if($Result && $Stmt->rowCount() > 0){

        while($ExchangeTradedAsset = $Stmt->fetch()){

            foreach($Template as $Country => $Info){

                if($Info['currency']['symbol'] == $ExchangeTradedAsset['currency_symbol'] && $Info['currency']['name'] == $ExchangeTradedAsset['currency_name']){

                    $Template[$Country]['asset_information'][$ExchangeTradedAsset['yfinance_ticker']] = [];

                    if(!empty($ExchangeTradedAsset['dividend_frequency'])) //reduces processing time by setting the value if it exists so it does not need to be estimated
                        $Template[$Country]['asset_information'][$ExchangeTradedAsset['yfinance_ticker']]['dividend_frequency'] = $ExchangeTradedAsset['dividend_frequency'];

                }

            }

        }

    }

} catch (Exception $Ex) {

    echo $Ex->getMessage()."\n";

}