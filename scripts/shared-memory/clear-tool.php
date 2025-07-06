<?php

require __DIR__.'/../../vendor/autoload.php';

use App\Services\SharedMemory;


$StringsToRemove = [];
$RemoveAll = false;

if(!empty($StringsToRemove))
    $StringsToRemove = array_flip($StringsToRemove);
else
    $RemoveAll = true;

try{

    $SharedMemoryKeys = (new SharedMemory())->listSharedMemoryKeys();

    echo "\nShared Memory Clear\n\n";

    foreach($SharedMemoryKeys as $Key){

        try{

            $ID = hexdec($Key);
            $SharedMemory = new SharedMemory();
            $SharedMemory->setID($ID);
            $SharedMemory->fill();
            $SharedMemoryData = $SharedMemory->read(true);

            if(!isset($SharedMemoryData['Control']['String']) || !isset($SharedMemoryData['Control']['ID']))
                continue;

            if($RemoveAll || isset($StringsToRemove[$SharedMemoryData['Control']['String']])){
                $SharedMemory->delete();
                echo $SharedMemoryData['Control']['String']." - deleted\n";
            }

        } catch (Exception $Ex) {

            echo $Ex->getMessage()."\n";

        }

    }

} catch (Exception $Ex) {

    echo $Ex->getMessage()."\n";

}

