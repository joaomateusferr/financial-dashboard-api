<?php

require dirname(__DIR__, 1).'/config.php';

use App\Helpers\APIRequestHelper;

try{

    $SID = 'sid=fdf91b526297c3e190042e9b78543173c3d939bf7b545414e03181a30c0291eb';
    $Response = APIRequestHelper::sendRequest('GET', '/user', [], ["Cookie: ".$SID]);
    var_dump($Response);exit;

} catch (Exception $Ex) {

    echo$Ex->getMessage();

}
