<?php

$ServersListPath = isset($argv[1]) ? $argv[1] : exit(1); //Fill in the servers list file path!

require __DIR__.'/../../vendor/autoload.php';

use App\Services\SharedMemory;
use App\Constants\KeysConstants;

if(pathinfo($ServersListPath, PATHINFO_EXTENSION) != 'json')
    exit(2); //Servers list file is not a json!

$Json = file_get_contents($ServersListPath);

//if(!json_validate($Json))
    //exit(3); //Servers list file is not formatted as json!

$ServersList = json_decode($Json, true);

try{

    $SharedMemory = new SharedMemory(KeysConstants::getServersList());
    $SharedMemory->write($ServersList);
    var_dump($SharedMemory->read(true));

} catch (Exception $Ex) {

    error_log($Ex->getMessage());
    exit(4); //Exception!

}