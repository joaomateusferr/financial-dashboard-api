<?php

$FilePath = isset($argv[1]) ? $argv[1] : exit;
$CustomerID = isset($argv[2]) ? $argv[2] : exit;

require_once dirname(__FILE__)."/../settings/configuration_file.php";

try{

    $CustomerServer = (new Customer($CustomerID))->getServer();

    $CustomerDatabaseConnection = new MariaDB($CustomerServer, "c_$CustomerID");

    $Sql = 'SELECT id, asset_id, financial_institution_id FROM exchange_traded_assets';
    $Stmt = $CustomerDatabaseConnection->prepare($Sql);
    $Result = $Stmt->execute();

    $CostumerExchangeTradedAssets = $CustomerAssetIDs = $CustomerFinancialInstitutions = [];

    if($Result && $Stmt->rowCount() > 0){

        while($Row = $Stmt->fetch()){

            $CustomerAssetIDs[] = $Row['asset_id'];
            $CustomerFinancialInstitutions[] = $Row['financial_institution_id'];
            $CostumerExchangeTradedAssets[$Row['id']] = ['ticker' => $Row['asset_id'], 'financial_institution' => $Row['financial_institution_id']];

        }

    }

    $CustomerFinancialInstitutions = array_keys(array_flip($CustomerFinancialInstitutions));

    $CommonInformationConnection = new MariaDB('common-information', 'common_information');

    $Sql = 'SELECT id, name FROM financial_institutions WHERE id IN ("'.implode('","', $CustomerFinancialInstitutions).'")';
    $Stmt = $CommonInformationConnection->prepare($Sql);
    $Result = $Stmt->execute();

    $FinancialInstitutions = [];

    if($Result && $Stmt->rowCount() > 0){

        while($Row = $Stmt->fetch()){
            $FinancialInstitutions[$Row['id']] = $Row['name'];
        }

    }

    $CustomerAssetIDs = array_keys(array_flip($CustomerAssetIDs));

    $Sql = 'SELECT id, ticker FROM exchange_traded_assets WHERE id IN ("'.implode('","', $CustomerAssetIDs).'")';
    $Stmt = $CommonInformationConnection->prepare($Sql);
    $Result = $Stmt->execute();

    $ExchangeTradedAssets = [];

    if($Result && $Stmt->rowCount() > 0){

        while($Row = $Stmt->fetch()){

            $ExchangeTradedAssets[$Row['id']] = $Row['ticker'];

        }

    }

    $CommonInformationConnection->close();

    foreach($CostumerExchangeTradedAssets as $Key => $AssetInfo){

        $CostumerExchangeTradedAssets[$Key]['ticker'] = $ExchangeTradedAssets[$AssetInfo['ticker']];
        $CostumerExchangeTradedAssets[$Key]['financial_institution'] = $FinancialInstitutions[$AssetInfo['financial_institution']];

    }

    $File = fopen($FilePath, 'r');

    if(!$File){
        error_log("Unable to open CSV $FilePath");
        exit("Unable to open CSV $FilePath");
    }

    $LineCount = 0;

    while (($Line = fgetcsv($File)) !== FALSE) {

        $LineCount++;

        if($LineCount == 1)
            continue;

        $Content = [];

        foreach(Constants::getDividendImportCSVTemplate() as $Key => $Value){
            $Content[$Value] = trim($Line[$Key]);
        }

        $TimeTrokens = explode("/",$Content['Date']);
        $Content['Date'] = strtotime($TimeTrokens[0].'-'.$TimeTrokens[1].'-'.$TimeTrokens[2].' 00:00:00');

        $AssetID = '';

        foreach($CostumerExchangeTradedAssets as $Key => $ExchangeTradedAsset){

            if($Content['Ticker'] == $ExchangeTradedAsset['ticker'] && $Content['FinancialInstitution'] == $ExchangeTradedAsset['financial_institution']){

                $AssetID = $Key;
                break;

            }
        }

        $Content['AssetID'] = $AssetID;

        if(empty($Content['Type']))
            $Content['Type'] = null;

        unset($Content['Ticker']);
        unset($Content['FinancialInstitution']);


        $Sql = 'INSERT INTO dividends VALUES (UUID(), :Date, :AssetID , :Type, :NumberOfShares, :AmountPaid, :Taxes, :NetAmountPaid, :ShareValue)';
        $Stmt = $CustomerDatabaseConnection->prepare($Sql);
        $Result = $Stmt->execute($Content);

    }

    $CustomerDatabaseConnection->close();

} catch (Exception $Ex) {

    error_log($Ex->getMessage()."\n");
    echo $Ex->getMessage()."\n";

}

