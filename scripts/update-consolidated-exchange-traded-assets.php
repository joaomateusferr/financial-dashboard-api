<?php

$CustomerID = isset($argv[1]) ? $argv[1] : exit;

require_once dirname(__FILE__)."/../settings/configuration_file.php";

try{

    $CustomersConnection = new MariaDB('customers', 'customers');

    $Sql = 'SELECT server  FROM customers WHERE id = ?';
    $Stmt = $CustomersConnection->prepare($Sql);
    $Result = $Stmt->execute([$CustomerID]);

    $Server = '';

    if($Result && $Stmt->rowCount() > 0)
        $CustomerServer = $Stmt->fetch()['server'];

    if(empty($CustomerServer)){
        error_log("Empty server for $CustomerID");
        exit("Empty server for $CustomerID");
    }

    $CustomersConnection->close();

    $CustomerDatabaseConnection = new MariaDB($CustomerServer, "c_$CustomerID");

    $Sql = 'SELECT DISTINCT asset_id FROM exchange_traded_assets';
    $Stmt = $CustomerDatabaseConnection->prepare($Sql);
    $Result = $Stmt->execute();

    $CustomerAssetIDs = [];

    if($Result && $Stmt->rowCount() > 0){

        $CustomerAssetIDs = $Stmt->fetchAll(PDO::FETCH_COLUMN);

    }

    $CommonInformationConnection = new MariaDB('common-information', 'common_information');

    $Sql = 'SELECT id, market_price, average_annual_dividend, payment_months FROM exchange_traded_assets WHERE id IN ("'.implode('","', $CustomerAssetIDs).'")';
    $Stmt = $CommonInformationConnection->prepare($Sql);
    $Result = $Stmt->execute();

    $ExchangeTradedAssets = [];

    if($Result && $Stmt->rowCount() > 0){

        while($Row = $Stmt->fetch()){

            $Infos = [];

            foreach($Row as $Key => $Value){

                $Infos[$Key] = $Value;

            }

            $ExchangeTradedAssets[$Row['id']] = $Infos;

        }

    }

    $CommonInformationConnection->close();

    $Sql = 'SELECT id, asset_id, number_of_shares, average_price FROM exchange_traded_assets';
    $Stmt = $CustomerDatabaseConnection->prepare($Sql);
    $Result = $Stmt->execute();

    if($Result && $Stmt->rowCount() > 0){

        while($Row = $Stmt->fetch()){

            $ExchangeTradedAssets[$Row['asset_id']]['number_of_shares'][] = $Row['number_of_shares'];
            $ExchangeTradedAssets[$Row['asset_id']]['average_price'][] = $Row['average_price'];
            $ExchangeTradedAssets[$Row['asset_id']]['customer_asset_ids'][] = $Row['id'];

        }

    }

    $Dividends = [];

    $Sql = 'SELECT exchange_traded_asset_id, SUM(net_amount_paid) AS net_amount_paid FROM dividends GROUP BY exchange_traded_asset_id;';
    $Stmt = $CustomerDatabaseConnection->prepare($Sql);
    $Result = $Stmt->execute();

    if($Result && $Stmt->rowCount() > 0){

        while($Row = $Stmt->fetch()){

            $Dividends[$Row['exchange_traded_asset_id']] = $Row['net_amount_paid'];

        }

    }

    foreach($ExchangeTradedAssets as $AssetID => $ExchangeTradedAsset){

        if(count($ExchangeTradedAsset['number_of_shares']) > 1){

            $TotalNumberOfShares = array_sum($ExchangeTradedAsset['number_of_shares']);
            $TotalAveragePrice = 0;

            foreach($ExchangeTradedAsset['number_of_shares'] as $Key => $NumberOfShares){

                $Multiplier = $NumberOfShares/$TotalNumberOfShares;
                $TotalAveragePrice = $TotalAveragePrice + ($ExchangeTradedAsset['average_price'][$Key] * $Multiplier);

            }

            $ExchangeTradedAssets[$AssetID]['number_of_shares'] = $TotalNumberOfShares;
            $ExchangeTradedAssets[$AssetID]['average_price'] = round($TotalAveragePrice ,3);

        } else {
            $ExchangeTradedAssets[$AssetID]['number_of_shares'] = $ExchangeTradedAsset['number_of_shares'][0];
            $ExchangeTradedAssets[$AssetID]['average_price'] = $ExchangeTradedAsset['average_price'][0];
        }

        if(empty($ExchangeTradedAssets[$AssetID]['average_price']) || empty($ExchangeTradedAssets[$AssetID]['number_of_shares'])){
            unset($ExchangeTradedAssets[$AssetID]);
            continue;
        }

        $ExchangeTradedAssets[$AssetID]['monetary_return_by_share'] = round($ExchangeTradedAssets[$AssetID]['market_price'] - $ExchangeTradedAssets[$AssetID]['average_price'], 3);
        $ExchangeTradedAssets[$AssetID]['monetary_return'] = $ExchangeTradedAssets[$AssetID]['monetary_return_by_share'] * $ExchangeTradedAssets[$AssetID]['number_of_shares'];
        $ExchangeTradedAssets[$AssetID]['percentage_return'] = round(($ExchangeTradedAssets[$AssetID]['monetary_return_by_share'] / $ExchangeTradedAssets[$AssetID]['average_price']) * 100, 3);

        if(isset($ExchangeTradedAssets[$AssetID]['average_annual_dividend'])){

            $ExchangeTradedAssets[$AssetID]['average_dividend'] = round($ExchangeTradedAssets[$AssetID]['average_annual_dividend']/count(json_decode($ExchangeTradedAssets[$AssetID]['payment_months'] ,true)),3);

            $ExchangeTradedAssets[$AssetID]['average_monthly_dividend'] = round($ExchangeTradedAssets[$AssetID]['average_annual_dividend']/12, 3);
            $ExchangeTradedAssets[$AssetID]['magic_number'] = (int) round($ExchangeTradedAssets[$AssetID]['market_price']/$ExchangeTradedAssets[$AssetID]['average_dividend'], 3);

            if($ExchangeTradedAssets[$AssetID]['average_price'] + ($ExchangeTradedAssets[$AssetID]['monetary_return_by_share']*-1) < 0)
                $ExchangeTradedAssets[$AssetID]['payback_period_in_months'] = 0;
            else
                $ExchangeTradedAssets[$AssetID]['payback_period_in_months'] = round(($ExchangeTradedAssets[$AssetID]['average_price'] + $ExchangeTradedAssets[$AssetID]['monetary_return_by_share']*-1) / $ExchangeTradedAssets[$AssetID]['average_monthly_dividend'], 3);

            if($ExchangeTradedAssets[$AssetID]['payback_period_in_months'] > 0)
                $ExchangeTradedAssets[$AssetID]['payback_period_in_years'] = round($ExchangeTradedAssets[$AssetID]['payback_period_in_months']/12 ,3);
            else
                $ExchangeTradedAssets[$AssetID]['payback_period_in_years'] = 0;

            $ExchangeTradedAssets[$AssetID]['dividend_only_payback_period_in_months'] = round($ExchangeTradedAssets[$AssetID]['average_price']/$ExchangeTradedAssets[$AssetID]['average_monthly_dividend'], 3);

            if($ExchangeTradedAssets[$AssetID]['dividend_only_payback_period_in_months'] > 0)
                $ExchangeTradedAssets[$AssetID]['dividend_only_payback_period_in_years'] = round($ExchangeTradedAssets[$AssetID]['dividend_only_payback_period_in_months']/12 ,3);
            else
                $ExchangeTradedAssets[$AssetID]['dividend_only_payback_period_in_years'] = 0;

            $SumNetDividendsPaid = 0;

            foreach($ExchangeTradedAssets[$AssetID]['customer_asset_ids'] as $CustomerAssetID){

                if(isset($Dividends[$CustomerAssetID]))
                    $SumNetDividendsPaid = $SumNetDividendsPaid + $Dividends[$CustomerAssetID];

            }

            $ExchangeTradedAssets[$AssetID]['sum_net_dividends_paid'] = round($SumNetDividendsPaid, 3);
            $ExchangeTradedAssets[$AssetID]['monetary_return_with_dividends'] = round(($ExchangeTradedAssets[$AssetID]['monetary_return_by_share'] * $ExchangeTradedAssets[$AssetID]['number_of_shares']) + $ExchangeTradedAssets[$AssetID]['sum_net_dividends_paid'], 3);
            $ExchangeTradedAssets[$AssetID]['percentage_return_with_dividends'] = round(((($ExchangeTradedAssets[$AssetID]['monetary_return_by_share'] * $ExchangeTradedAssets[$AssetID]['number_of_shares']) + $ExchangeTradedAssets[$AssetID]['sum_net_dividends_paid']) / ($ExchangeTradedAssets[$AssetID]['average_price'] * $ExchangeTradedAssets[$AssetID]['number_of_shares'])) * 100, 3);

        }

    }

    $Sql = 'TRUNCATE consolidated_exchange_traded_assets';
    $Stmt = $CustomerDatabaseConnection->prepare($Sql);
    $Result = $Stmt->execute();

    foreach($ExchangeTradedAssets as $AssetID => $ExchangeTradedAsset){

        //prepare array to insert

        $KeysToRemove = ['market_price', 'average_annual_dividend', 'average_dividend', 'average_monthly_dividend', 'customer_asset_ids', 'payment_months', 'monetary_return_by_share'];

        foreach($KeysToRemove as $Key){

            unset($ExchangeTradedAssets[$AssetID][$Key]);

        }

        $KeysToHave = ['id', 'number_of_shares', 'average_price', 'magic_number', 'sum_net_dividends_paid', 'monetary_return', 'percentage_return', 'monetary_return_with_dividends', 'percentage_return_with_dividends', 'payback_period_in_months', 'payback_period_in_years', 'dividend_only_payback_period_in_months', 'dividend_only_payback_period_in_years'];

        foreach($KeysToHave as $Key){

            if(!isset($ExchangeTradedAssets[$AssetID][$Key]))
                $ExchangeTradedAssets[$AssetID][$Key] = 0;

        }

        $Sql = 'INSERT INTO consolidated_exchange_traded_assets VALUES (UUID(), :id, :number_of_shares, :average_price, :magic_number, :sum_net_dividends_paid, :monetary_return, :percentage_return, :monetary_return_with_dividends, :percentage_return_with_dividends, :payback_period_in_months, :payback_period_in_years, :dividend_only_payback_period_in_months, :dividend_only_payback_period_in_years)';
        $Stmt = $CustomerDatabaseConnection->prepare($Sql);
        $Result = $Stmt->execute($ExchangeTradedAssets[$AssetID]);

    }


} catch (Exception $Ex) {

    error_log($Ex->getMessage()."\n");
    echo $Ex->getMessage()."\n";

}

