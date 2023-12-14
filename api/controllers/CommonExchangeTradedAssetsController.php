<?php

class CommonExchangeTradedAssetsController {

    public static function getCommonExchangeTradedAssets() : array {

        try{

            $CommonExchangeTradedAssets = [];

            $CommonInformationConnection = new MariaDB('common-information', 'common_information');
            $Sql = 'SELECT id, ticker FROM exchange_traded_assets';
            $Stmt = $CommonInformationConnection->prepare($Sql);
            $Result = $Stmt->execute();

            if($Result && $Stmt->rowCount() > 0){

                while($ExchangeTradedAsset = $Stmt->fetch()){

                    $CommonExchangeTradedAssets[$ExchangeTradedAsset['id']] = ['ticker' => $ExchangeTradedAsset['ticker']];

                }

            }

        } catch (Exception $Ex) {

            error_log($Ex->getMessage()."\n");

        }

        return $CommonExchangeTradedAssets;
    }

}