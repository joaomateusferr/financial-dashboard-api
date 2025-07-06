<?php

require __DIR__.'/../../vendor/autoload.php';

use App\Services\SharedMemory;

try{

    $SharedMemoryKeys = (new SharedMemory())->listSharedMemoryKeys();

    echo "\nShared Memory Status\n\n";

    foreach($SharedMemoryKeys as $Key){

        try{

            $ID = hexdec($Key);
            $SharedMemory = new SharedMemory();
            $SharedMemory->setID($ID);
            $SharedMemory->fill();
            $SharedMemoryData = $SharedMemory->read(true);

            if(!isset($SharedMemoryData['Control']['ID']) || !isset($SharedMemoryData['Control']['String']))
                continue;

            echo "Key - $Key\nID - $ID\nString - ".$SharedMemoryData['Control']['String']."\nContent:\n";

            foreach($SharedMemoryData['Data'] as $Key => $Value){
                echo "$Key -> $Value\n";
            }

            echo "\n\n";


        } catch (Exception $Ex) {

            echo $Ex->getMessage()."\n";

        }

    }

} catch (Exception $Ex) {

    echo $Ex->getMessage()."\n";

}

