<?php

namespace App\Repositories;

use App\Services\MariaDB;
use App\Services\Investidor10;
use \Exception;

class ExchangeTradedAssetsRepository {

    public static function get(array $Assets) : array {

        $AssetsDetails = [];

        try{

            $KernelConnection = new MariaDB('kernel', 'common_information');
            $Sql = "SELECT ID, Ticker, AssetQualificationID, ExchangeID, MarketPrice, UpdateDate, AverageAnnualDividend, NetAverageAnnualDividend, AssetTypeID, AssetSubtypeID, IsoCode FROM exchange_traded_assets WHERE Ticker IN (".implode(',', array_fill(0, count($Assets), '?')).")";
            $Stmt = $KernelConnection->prepare($Sql);
            $Result = $Stmt->execute($Assets);

            if($Result && $Stmt->rowCount() > 0){

                while($Row = $Stmt->fetch()){

                    foreach($Row as $Field => $Value){

                        if(is_null($Value))
                            unset($Row[$Field]);

                    }

                    $Ticker = $Row['Ticker'];
                    unset($Row['Ticker']);
                    $AssetsDetails[$Ticker] = $Row;

                }

            }

        } catch (Exception $Exception){

            return [];
           //add logs here

        } finally {

            $KernelConnection->close();

        }

        return $AssetsDetails;

    }

    public static function insert(array $Assets) : array {

        try{

            $KernelConnection = new MariaDB('kernel', 'common_information');

            $AssetsToUpdate = [];

            foreach($Assets as $Asset){

                $AssetQualificationStrng = empty($Asset['AssetQualificationID']) ? "AssetQualificationID IS NULL" : "AssetQualificationID = :AssetQualificationID";

                $Sql = "SELECT ID FROM exchange_traded_assets WHERE Ticker = :Ticker AND $AssetQualificationStrng";
                $Stmt = $KernelConnection->prepare($Sql);
                $Stmt->bindValue(':Ticker', $Asset['Ticker']);

                if(!empty($Asset['AssetQualificationID']))
                    $Stmt->bindValue(':AssetQualificationID', $Asset['AssetQualificationID']);

                $Result = $Stmt->execute();

                if($Result && $Stmt->rowCount() > 0){

                    $AssetsToUpdate[] = ['Ticker' => $Asset['Ticker'], 'AssetQualificationID' => $Asset['AssetQualificationID']];
                    continue;

                }

                $Sql = "INSERT INTO exchange_traded_assets (Ticker, AssetQualificationID, ExchangeID, UpdateDate, AssetTypeID, AssetSubtypeID, IsoCode) VALUES (:Ticker, :AssetQualificationID, :ExchangeID, :UpdateDate, :AssetTypeID, :AssetSubtypeID, :IsoCode)";
                $Stmt = $KernelConnection->prepare($Sql);
                $Result = $Stmt->execute($Asset);

                if($Result)
                    $AssetsToUpdate[] = ['Ticker' => $Asset['Ticker'], 'AssetQualificationID' => $Asset['AssetQualificationID']];

            }

            return $AssetsToUpdate;

        } catch (Exception $Exception){

            return[];
           //add logs here

        } finally {

            $KernelConnection->close();

        }

    }

    public static function update(array $AssetsToUpdate) : array {

        try{

            $KernelConnection = new MariaDB('kernel', 'common_information');

            $Assets = [];
            $AssetTypeByAssetID = [];

            foreach($AssetsToUpdate as $Asset){

                $AssetDetails = [];

                $AssetQualificationStrng = empty($Asset['AssetQualificationID']) ? "AssetQualificationID IS NULL" : "AssetQualificationID = :AssetQualificationID";

                $Sql = "SELECT ID, AssetTypeID FROM exchange_traded_assets WHERE Ticker = :Ticker AND $AssetQualificationStrng";
                $Stmt = $KernelConnection->prepare($Sql);
                $Stmt->bindValue(':Ticker', $Asset['Ticker']);

                if(!empty($Asset['AssetQualificationID']))
                    $Stmt->bindValue(':AssetQualificationID', $Asset['AssetQualificationID']);

                $Result = $Stmt->execute();

                $FullTicker = $Asset['Ticker'];

                if(!empty($Asset['AssetQualificationID']))
                    $FullTicker .= $Asset['AssetQualificationID'];

                if($Result && $Stmt->rowCount() > 0){

                    $AssetDetails = $Stmt->fetch();
                    $Assets[$AssetDetails['ID']] = ['FullTicker' => $FullTicker, 'AssetType' => $AssetDetails['AssetTypeID']];
                    $AssetTypeByAssetID[$AssetDetails['ID']] = $AssetDetails['AssetTypeID'];

                }

            }

            $AssetTypeIDs = array_unique(array_values($AssetTypeByAssetID));

            $AssetTypesIdentifierByID = [];

            $Sql = "SELECT ID, Identifier FROM asset_types WHERE ID IN (".implode(',', array_fill(0, count($AssetTypeIDs), '?')).")";
            $Stmt = $KernelConnection->prepare($Sql);
            $Result = $Stmt->execute($AssetTypeIDs);

            if($Result && $Stmt->rowCount() > 0){

                while($Row = $Stmt->fetch()){

                    $AssetTypesIdentifierByID[$Row['ID']] = $Row['Identifier'];

                }

            }

            $Investidor10Assets = [];

            foreach($Assets as $ID => $Asset){

                $Investidor10Assets[$Asset['FullTicker']] = $AssetTypesIdentifierByID[$Assets[$ID]['AssetType']];

            }

            $AssetsData = Investidor10::getAssetsData($Investidor10Assets);

            $AssetsResult = ['Failure' => [], 'Success' => []];

            foreach($Assets as $ID => $Asset){


                if(isset($AssetsData[$Asset['FullTicker']])){

                    $Sql = 'UPDATE exchange_traded_assets SET MarketPrice = :MarketPrice, AverageAnnualDividend = :AverageAnnualDividend, NetAverageAnnualDividend = :NetAverageAnnualDividend, UpdateDate = :UpdateDate WHERE ID = :ID';
                    $Stmt = $KernelConnection->prepare($Sql);
                    $Stmt->bindValue(':MarketPrice', $AssetsData[$Asset['FullTicker']]['MarketPrice']);
                    $Stmt->bindValue(':AverageAnnualDividend', $AssetsData[$Asset['FullTicker']]['AnnualPayment']);
                    $Stmt->bindValue(':NetAverageAnnualDividend', $AssetsData[$Asset['FullTicker']]['NetAnnualPayment']);
                    $Stmt->bindValue(':UpdateDate', time());
                    $Stmt->bindValue(':ID', $ID);
                    $Result = $Stmt->execute();

                    if($Result)
                        $AssetsResult['Success'][] = $Asset['FullTicker'];
                    else
                        $AssetsResult['Failure'][] = $Asset['FullTicker'];

                } else {

                    $AssetsResult['Failure'][] = $Asset['FullTicker'];

                }

            }

            return $AssetsResult;

        } catch (Exception $Exception){

            return[];
           //add logs here

        } finally {

            $KernelConnection->close();

        }

    }

}