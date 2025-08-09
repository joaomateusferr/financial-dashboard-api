<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Helpers\Investidor10WebParser;

try{

    $Investidor10Parser = new Investidor10WebParser('https://investidor10.com.br/stocks/aapl/');

    var_dump($Investidor10Parser->getMarketPrice());

    if(isset($Investidor10Parser->getDividendsHistory()[0]))
        var_dump($Investidor10Parser->getDividendsHistory()[0]);

} catch (Exception $Ex) {

    echo$Ex->getMessage();

}
