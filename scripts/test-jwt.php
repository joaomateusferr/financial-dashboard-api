<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Helpers\JwtHelper;

try{

    $Jwt = JwtHelper::create(10, 'ADMIN');
    var_dump($Jwt);
    $Result = JwtHelper::parse($Jwt);
    var_dump($Result);

} catch (Exception $Ex) {

    echo$Ex->getMessage();

}
