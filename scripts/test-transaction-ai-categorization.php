<?php

//shell - set env -> export OPENAI_API_KEY=""

//$GLOBALS["DebugAiApiResponse"] = true;

$Transaction = isset($argv[1]) ? $argv[1] : exit("Provide a transaction as a parameter!\n");

use App\Services\TransactionAiCategorization;

try {

    $AICategorization = new TransactionAiCategorization();
    $Categories = $AICategorization->categorize($Transaction);
    echo $Categories[0]."\n";

} catch (Exception $Exception) {
    echo $Exception->getMessage()."\n";
}