<?php

namespace App\Repositories;

use App\Services\MariaDB;
use \Exception;

class ExchangeRepository {

    public static function getExchangesByID(array $ExchangeIDs, array $Fields) : ?array {

        $ExchangesDetails = [];

        try{

            if(!in_array('ID', $Fields))
                $Fields[] = 'ID';

            $FieldsString = implode(', ', $Fields);
            $KernelConnection = new MariaDB('kernel', 'common_information');
            $Sql = "SELECT $FieldsString FROM exchanges WHERE ID IN (".implode(',', array_fill(0, count($ExchangeIDs), '?')).")";
            $Stmt = $KernelConnection->prepare($Sql);
            $Result = $Stmt->execute($ExchangeIDs);

            if($Result && $Stmt->rowCount() > 0){

                while($Row = $Stmt->fetch()){

                    $ID = $Row['ID'];
                    unset($Row['ID']);
                    $ExchangesDetails[$ID] = $Row;

                }

            }

        } catch (Exception $Exception){

           //add logs here
           return null;

        } finally {

            $KernelConnection->close();

        }

        return $ExchangesDetails;

    }

    public static function getExchanges(array $Exchanges) : array {

        $ExchangesDetails = [];

        try{

            $KernelConnection = new MariaDB('kernel', 'common_information');
            $Sql = "SELECT ID, YFinanceAlias, Name, Alias FROM exchanges WHERE Alias IN (".implode(',', array_fill(0, count($Exchanges), '?')).")";
            $Stmt = $KernelConnection->prepare($Sql);
            $Result = $Stmt->execute($Exchanges);

            if($Result && $Stmt->rowCount() > 0){

                while($Row = $Stmt->fetch()){

                    $Alias = $Row['Alias'];
                    unset($Row['Alias']);
                    $ExchangesDetails[$Alias] = $Row;

                }

            }

        } catch (Exception $Exception){

           //add logs here
           return [];

        } finally {

            $KernelConnection->close();

        }

        return $ExchangesDetails;

    }

}