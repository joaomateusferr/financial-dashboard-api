<?php

namespace App\Repositories;

use App\Services\MariaDB;
use \Exception;

class AssetTypeRepository {

    public static function getAssetTypes(array $Identifiers) : array {

        $AssetTypes = [];

        try{

            $KernelConnection = new MariaDB('kernel', 'common_information');
            $Sql = "SELECT ID, Identifier, Name FROM asset_types WHERE Identifier IN (".implode(',', array_fill(0, count($Identifiers), '?')).")";
            $Stmt = $KernelConnection->prepare($Sql);
            $Result = $Stmt->execute($Identifiers);

            if($Result && $Stmt->rowCount() > 0){

                while($Row = $Stmt->fetch()){

                    $Identifier = $Row['Identifier'];
                    unset($Row['Identifier']);
                    $AssetTypes[$Identifier] = $Row;

                }

            }

        } catch (Exception $Exception){

           //add logs here
           return [];

        } finally {

            $KernelConnection->close();

        }

        return $AssetTypes;

    }

}