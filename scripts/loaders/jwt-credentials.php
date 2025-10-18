<?php

$CredentialsPath = isset($argv[1]) ? $argv[1] : exit(1); //Fill in the credentials file path!

require __DIR__.'/../../vendor/autoload.php';

use App\Services\SharedMemory;
use App\Constants\KeysConstants;

if(pathinfo($CredentialsPath, PATHINFO_EXTENSION) != 'json')
    exit(2); //Credentials file is not a json!

$Json = file_get_contents($CredentialsPath);

if(!json_validate($Json))
    exit(3);   //Credentials file is not formatted as json!

$Credentials = json_decode($Json, true);

try{

    $SharedMemory = new SharedMemory(KeysConstants::getJwtCredentials());
    $SharedMemory->write($Credentials);
    var_dump($SharedMemory->read(true));

} catch (Exception $Ex) {

    error_log($Ex->getMessage());
    exit(4);   //Exception!

}