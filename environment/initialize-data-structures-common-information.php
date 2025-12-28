<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Services\MariaDB;

try{

    $Connection = new MariaDB('common-information');

    $Queries = [
        "CREATE DATABASE common_information",
        "USE common_information",
        "CREATE TABLE financial_institutions (
            ID SERIAL PRIMARY KEY,
            Name VARCHAR(255) NOT NULL UNIQUE,
            Alias VARCHAR(255) DEFAULT NULL
        )",
        "CREATE TABLE currencys (
            IsoCode CHAR(3) NOT NULL PRIMARY KEY,
            Name VARCHAR(100) NOT NULL,
            Symbol VARCHAR(10) DEFAULT NULL
        )",
        "CREATE TABLE asset_qualifications (
            ID INT NOT NULL PRIMARY KEY,
            Name VARCHAR(255) NOT NULL
        )",
        "CREATE TABLE asset_types (
            ID SERIAL PRIMARY KEY,
            Identifier VARCHAR(12) NOT NULL UNIQUE,
            Name VARCHAR(255) NOT NULL
        )",
        "CREATE TABLE exchanges (
            ID SERIAL PRIMARY KEY,
            YFinanceAlias VARCHAR(30) DEFAULT NULL,
            Name VARCHAR(255) NOT NULL,
            Alias VARCHAR(255) DEFAULT NULL
        )",
        "CREATE TABLE exchange_traded_assets (
            ID SERIAL PRIMARY KEY,
            Ticker VARCHAR(20) NOT NULL,
            AssetQualificationID INT DEFAULT NULL,
            ExchangeID BIGINT UNSIGNED DEFAULT NULL,
            MarketPrice DECIMAL(18,4) NOT NULL DEFAULT 0,
            UpdateDate INT DEFAULT NULL,
            AverageAnnualDividend DECIMAL(18,4) NOT NULL DEFAULT 0,
            NetAverageAnnualDividend DECIMAL(18,4) NOT NULL DEFAULT 0,
            AssetTypeID BIGINT UNSIGNED NOT NULL,
            AssetSubtypeID BIGINT UNSIGNED DEFAULT NULL,
            IsoCode CHAR(3) NOT NULL,
            FOREIGN KEY (AssetQualificationID) REFERENCES asset_qualifications(ID),
            FOREIGN KEY (ExchangeID) REFERENCES exchanges(ID),
            FOREIGN KEY (AssetTypeID) REFERENCES asset_types(ID),
            FOREIGN KEY (AssetSubtypeID) REFERENCES asset_types(ID),
            FOREIGN KEY (IsoCode) REFERENCES currencys(IsoCode)
        )",
        "CREATE INDEX IDX_AssetTypeID ON exchange_traded_assets (AssetTypeID)",
        "CREATE INDEX IDX_IsoCode ON exchange_traded_assets (IsoCode)",
    ];

    foreach($Queries as $Sql){

        $Stmt = $Connection->prepare($Sql);
        $Result = $Stmt->execute();

    }

} catch (Exception $Exception) {

    echo $Exception->getMessage()."\n";

} finally {

    $Connection->close();

}