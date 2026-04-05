<?php

$GLOBALS["BaseURL"] = 'http://localhost:8888/';
$GLOBALS["Issue"] = [];

$User = isset($argv[1]) ? $argv[1] : exit;
$Password = isset($argv[2]) ? $argv[2] : exit;
$TemplatePath = isset($argv[3]) ? $argv[3] : exit;

if (!file_exists($TemplatePath))
    exit;

if(pathinfo($TemplatePath, PATHINFO_EXTENSION) != 'json')
    exit;

$Assets = file_get_contents($TemplatePath);

if(!json_validate($Assets))
    exit;

$Assets = json_decode($Assets, true);

$AssetTypes = [];
$AssetQualifications = [];
$Exchanges = [];

foreach($Assets as $Asset){

    if(isset($Asset['Qualification']) && !isset($AssetQualifications[$Asset['Qualification']]))
        $AssetQualifications[$Asset['Qualification']] = 1;

    if(!isset($AssetTypes[$Asset['Type']]))
        $AssetTypes[$Asset['Type']] = 1;

    if(isset($Asset['Subtype']) && !isset($AssetTypes[$Asset['Subtype']]))
        $AssetTypes[$Asset['Subtype']] = 1;

    if(isset($Asset['Exchange']) && !isset($Exchanges[$Asset['Exchange']]))
        $Exchanges[$Asset['Exchange']] = 1;

}

$AssetTypes = array_keys($AssetTypes);
$AssetQualifications = array_keys($AssetQualifications);
$Exchanges = array_keys($Exchanges);

$ApiLimits = getApiLimits();

if(empty($ApiLimits) && !empty($GLOBALS["Issue"]))
    exit(var_export($GLOBALS["Issue"])."\nApi limits issue!\n");

$AssetQualificationsDetails = getAssetQualificationsDetails($AssetQualifications);

if(empty($AssetQualificationsDetails) && !empty($GLOBALS["Issue"]))
    exit(var_export($GLOBALS["Issue"])."\nAsset qualifications details issue!\n");

if(empty($AssetQualificationsDetails))
    exit("Asset qualifications details is empty!\n");

$AssetTypesDetails = getAssetTypesDetails($AssetTypes);

if(empty($AssetTypesDetails) && !empty($GLOBALS["Issue"]))
    exit(var_export($GLOBALS["Issue"])."\nAsset types details issue!\n");

if(empty($AssetTypesDetails))
    exit("Asset types details is empty!\n");

$ExchangesDetails = getExchangesDetails($Exchanges);

if(empty($ExchangesDetails) && !empty($GLOBALS["Issue"]))
    exit(var_export($GLOBALS["Issue"])."\nExchanges details issue!\n");

if(empty($ExchangesDetails))
    exit("Exchanges details is empty!\n");

$AssetsToAdd = [];

foreach($Assets as $Asset){

    if(isset($Asset['Qualification']) && !isset($AssetQualificationsDetails[$Asset['Qualification']]))  //ignore invalids
        continue;

    $AssetToAdd = [];

    $AssetToAdd['Ticker'] = $Asset['Ticker'];

    if(isset($Asset['Qualification']))
        $AssetToAdd['AssetQualificationID'] = $Asset['Qualification'];

    if(isset($Asset['Exchange']) && isset($ExchangesDetails[$Asset['Exchange']]))
        $AssetToAdd['ExchangeID'] = $ExchangesDetails[$Asset['Exchange']]['ID'];

    if(isset($AssetTypesDetails[$Asset['Type']]))
        $AssetToAdd['AssetTypeID'] = $AssetTypesDetails[$Asset['Type']]['ID'];

    if(isset($Asset['Subtype']) && isset($AssetTypesDetails[$Asset['Subtype']]))
        $AssetToAdd['AssetSubtypeID'] = $AssetTypesDetails[$Asset['Subtype']]['ID'];

    $AssetToAdd['IsoCode'] = $Asset['IsoCode'];

    $AssetsToAdd[] = $AssetToAdd;

}

unset($Assets);
unset($AssetTypesDetails);
unset($AssetQualificationsDetails);
unset($ExchangesDetails);

$Cookie = login($User, $Password);

if(empty($Cookie))
    exit("Login issue!\n");

echo "Logged in successfully!\n";

$Chunks = array_chunk($AssetsToAdd, $ApiLimits['common-information-exchange-traded-assets-post']);

$Response = ['Failure' => [], 'Success' => []];

echo "Loading assets...\n";

foreach($Chunks as $Chunk){

    $Options = [ 'http' => ['header'  => "Content-type: application/json\r\nCookie: $Cookie\r\n",'method'  => 'POST', 'content' => json_encode($Chunk)]];
    $Result = @file_get_contents($BaseURL.'common-information/exchange-traded-assets', false, stream_context_create($Options));

    if(empty($Result))
        continue;

    $Result = json_decode($Result, true);
    $Result = $Result['result'];

    if(empty($Result))
        continue;

    $Response['Failure'] = array_merge($Response['Failure'], $Result['Failure']);
    $Response['Success'] = array_merge($Response['Success'], $Result['Success']);

}

$Result = logout($Cookie);

if(empty($Result))
    exit("Logout issue!\n");

echo "Logout successfully!\n";

if(!empty($Response['Success'])){

    echo "Success:\n";

    foreach($Response['Success'] as $Success){

        echo "$Success\n";

    }

}

if(!empty($Response['Failure'])){

    echo "Failure:\n";

    foreach($Response['Failure'] as $Failure){

        echo "$Failure\n";

    }

}

function getApiLimits() : ?array {

    $Options = [ 'http' => ['method'  => 'GET']];
    $ApiLimits = @file_get_contents($GLOBALS["BaseURL"].'limits', false, stream_context_create($Options));

    if(empty($ApiLimits)){
        $GLOBALS["Issue"] = $http_response_header;
        return null;
    }

    $ApiLimits = json_decode($ApiLimits,true);
    $ApiLimits = $ApiLimits['result'];

    return $ApiLimits;

}

function getAssetQualificationsDetails(array $AssetQualifications) : ?array {

    $Options = [ 'http' => ['header'  => "Content-type: application/json",'method'  => 'GET', 'content' => json_encode($AssetQualifications)]];
    $AssetQualificationsDetails = @file_get_contents($GLOBALS["BaseURL"].'common-information/asset-qualification', false, stream_context_create($Options));

    if(empty($AssetQualificationsDetails)){
        $GLOBALS["Issue"] = $http_response_header;
        return null;
    }

    $AssetQualificationsDetails = json_decode($AssetQualificationsDetails,true);
    $AssetQualificationsDetails = $AssetQualificationsDetails['result'];

    return $AssetQualificationsDetails;

}

function getAssetTypesDetails(array $AssetTypes) : ?array {

    $Options = [ 'http' => ['header'  => "Content-type: application/json",'method'  => 'GET', 'content' => json_encode($AssetTypes)]];
    $AssetTypesDetails = @file_get_contents($GLOBALS["BaseURL"].'common-information/asset-type', false, stream_context_create($Options));

    if(empty($AssetTypesDetails)){
        $GLOBALS["Issue"] = $http_response_header;
        return null;
    }

    $AssetTypesDetails = json_decode($AssetTypesDetails,true);
    $AssetTypesDetails = $AssetTypesDetails['result'];

    return $AssetTypesDetails;

}

function getExchangesDetails(array $Exchanges) : ?array {

    $Options = [ 'http' => ['header'  => "Content-type: application/json",'method'  => 'GET', 'content' => json_encode($Exchanges)]];
    $ExchangesDetails = @file_get_contents($GLOBALS["BaseURL"].'common-information/exchange', false, stream_context_create($Options));

    if(empty($ExchangesDetails)){
        $GLOBALS["Issue"] = $http_response_header;
        return null;
    }

    $ExchangesDetails = json_decode($ExchangesDetails,true);
    $ExchangesDetails = $ExchangesDetails['result'];

    return $ExchangesDetails;

}

function login(string $User, string $Password) : ?string {

    $Cookie = null;
    $Options = [ 'http' => ['user_agent' => 'script','header'  => "Content-type: application/json",'method'  => 'POST', 'content' => json_encode(['Email' => $User, 'Password' => $Password])]];
    $Result = @file_get_contents($GLOBALS["BaseURL"].'session', false, stream_context_create($Options));

    if(!empty($Result)){

        foreach($http_response_header as $ResponseHeaderLine){

            if (preg_match('/^Set-Cookie:\s*([^;]+)/i', $ResponseHeaderLine, $Matches)) {

                $Cookie = $Matches[1];
                break;

            }

        }

    }

    return $Cookie;

}

function logout(string $Cookie) : bool {

    $Options = [ 'http' => ['header'  => "Cookie: $Cookie\r\n",'method'  => 'DELETE']];
    $Result = @file_get_contents($GLOBALS["BaseURL"].'session', false, stream_context_create($Options));

    if(!empty($Result))
        return true;

    return false;

}