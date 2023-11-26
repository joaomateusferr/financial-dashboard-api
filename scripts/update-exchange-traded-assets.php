<?php

require_once dirname(__FILE__)."/../settings/configuration_file.php";

$DefaultTemplateFolder = YFinanceAdapter::getDefaultTemplateFolder();
$TemplateFolder = $DefaultTemplateFolder.'/'.uniqid();

$Template = YFinanceAdapter::assembleTemplate();

$ServerRoot = explode("/", $ProjectPath);
unset($ServerRoot[array_key_last($ServerRoot)]);
$ServerRoot = implode("/", $ServerRoot);

$DividendMapMainPath = $ServerRoot.YFinanceAdapter::getDefaultDividendMapMainPath();

try{

    $CommonInformationConnection = new MariaDB('common-information', 'common_information');

    $Sql = 'SELECT exchange_traded_assets.yfinance_ticker, exchange_traded_assets.dividend_frequency, currencys.symbol as currency_symbol, currencys.name as currency_name FROM exchange_traded_assets INNER JOIN currencys ON currencys.id = exchange_traded_assets.currency_id';
    $Stmt = $CommonInformationConnection->prepare($Sql);
    $Result = $Stmt->execute();

    if($Result && $Stmt->rowCount() > 0){

        while($ExchangeTradedAsset = $Stmt->fetch()){

            foreach($Template as $Country => $Info){

                if($Info['currency']['symbol'] == $ExchangeTradedAsset['currency_symbol'] && $Info['currency']['name'] == $ExchangeTradedAsset['currency_name']){

                    $Template[$Country]['asset_information'][$ExchangeTradedAsset['yfinance_ticker']] = new ArrayObject();

                    if(!empty($ExchangeTradedAsset['dividend_frequency'])) //reduces processing time by setting the value if it exists so it does not need to be estimated
                        $Template[$Country]['asset_information'][$ExchangeTradedAsset['yfinance_ticker']]['dividend_frequency'] = $ExchangeTradedAsset['dividend_frequency'];

                }

            }

        }

    }


    if (is_dir($DefaultTemplateFolder))
        shell_exec("rm -r $DefaultTemplateFolder");

    mkdir($TemplateFolder, 0777, true);

    $TemplatePath = $TemplateFolder.'/'.uniqid().'.json';
    file_put_contents($TemplatePath, json_encode($Template, JSON_PRETTY_PRINT));

    $Output = [];
    $ResultCode = 0;
    $Command = "python3 $DividendMapMainPath $TemplatePath";

    exec($Command, $Output, $ResultCode);

    if($ResultCode != 0){
        echo "$ResultCode\n";
        echo var_export($Output, true)."\n";
        error_log("$ResultCode\n");
        error_log(var_export($Output, true)."\n");
        exit;
    }

    $Countries = array_keys($Template);

    $Assets = [];

    foreach($Countries as $Country){

        $CSVPath = "$TemplateFolder/$Country.csv";

        $File = fopen($CSVPath, 'r');

        if(!$File){
            error_log("Unable to open CSV $CSVPath");
            exit("Unable to open CSV $CSVPath");
        }

        $LineCount = 0;

        while (($Line = fgetcsv($File)) !== FALSE) {

            $LineCount++;

            if($LineCount == 1)
                continue;

            $Content = [];

            foreach(YFinanceAdapter::getDefaultExportAssetColumns() as $Key => $Value){
                $Content[$Value] = trim($Line[$Key]);
            }

            $YfinanceTicker = $Content['ticker'];
            unset($Content['ticker']);

            if(is_string($Content['market_price']))
                $Content['market_price'] = (float)$Content['market_price'];

            if(empty($Content['payment_months']) || $Content['payment_months'] == '-')
                $Content['payment_months'] = null;

            if(!empty($Content['payment_months']) && is_string($Content['payment_months']))
                $Content['payment_months'] = explode(', ', $Content['payment_months']);

            if(!empty($Content['payment_months'])){
                asort($Content['payment_months']);
                $Content['payment_months'] = array_values($Content['payment_months']);
                $Content['payment_months'] = json_encode($Content['payment_months']);
            }

            if(empty($Content['average_annual_dividend']) || $Content['average_annual_dividend'] == '-')
                $Content['average_annual_dividend'] = null;

            if(is_string($Content['average_annual_dividend']))
                $Content['average_annual_dividend'] = (float)$Content['average_annual_dividend'];

            if(empty($Content['average_monthly_dividend']) || $Content['average_monthly_dividend'] == '-')
                $Content['average_monthly_dividend'] = null;

            if(is_string($Content['average_monthly_dividend']))
                $Content['average_monthly_dividend'] = (float)$Content['average_monthly_dividend'];

            $Assets[$YfinanceTicker] = $Content;

        }

    }

    shell_exec("rm -r $DefaultTemplateFolder");

    foreach($Assets as $Key => $Asset){

        $Sql = "UPDATE exchange_traded_assets SET market_price = :market_price, payment_months = :payment_months, average_annual_dividend = :average_annual_dividend, average_monthly_dividend = :average_monthly_dividend WHERE yfinance_ticker = '$Key'";
        $Stmt = $CommonInformationConnection->prepare($Sql);
        $Result = $Stmt->execute($Asset);

    }

    $CommonInformationConnection->close();


} catch (Exception $Ex) {

    echo $Ex->getMessage()."\n";

}