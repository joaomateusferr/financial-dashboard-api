<?php

namespace App\Repositories;

use App\Services\MariaDB;
use \Exception;

class AssetQualificationRepository {

    public static function getAssetQualifications(array $Identifiers) : array {

        $AssetQualifications = [];

        try{

            $KernelConnection = new MariaDB('kernel', 'common_information');
            $Sql = "SELECT ID, Name FROM asset_qualifications WHERE ID IN (".implode(',', array_fill(0, count($Identifiers), '?')).")";
            $Stmt = $KernelConnection->prepare($Sql);
            $Result = $Stmt->execute($Identifiers);

            if($Result && $Stmt->rowCount() > 0){

                while($Row = $Stmt->fetch()){

                    $Identifier = $Row['ID'];
                    unset($Row['ID']);
                    $AssetQualifications[$Identifier] = $Row;

                }

            }

        } catch (Exception $Exception){

           //add logs here
           return [];

        } finally {

            $KernelConnection->close();

        }

        return $AssetQualifications;

    }

}