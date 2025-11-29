<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Services\SharedMemory;
use App\Constants\KeysConstants;

$Mode = isset($argv[1]) ? $argv[1] : 'CI';

$SupportedModes = ['CI', 'DEV', 'DB_ONLY'];
$DatabaseNetwork = 'network-db';
$DatabaseNetworkAlias = 'db';

if(!in_array($Mode, $SupportedModes))
    exit(1);    //Mode not supported

$Output = [];
$ResultCode = 0;

exec("openssl rand -base64 45 | tr -dc 'A-Za-z0-9-().!@?#,/;+' | head -c30", $Output, $ResultCode); //Creating a cryptographically strong random password

if(!empty($ResultCode) || !isset($Output[0]))
    exit(2);    //Unable to generate database password

$DatabasePassword = trim($Output[0]);

$Output = [];
$ResultCode = 0;

exec("hostname -I | awk '{print $2}'", $Output, $ResultCode);

if(!empty($ResultCode) || !isset($Output[0]))
    exit(3);    //Unable to get database host

$DatabaseHost = empty($Output[0]) ? $DatabaseNetworkAlias : trim($Output[0]);

$Output = [];
$ResultCode = 0;

exec("docker network create $DatabaseNetwork", $Output, $ResultCode);

if(!empty($ResultCode))
    exit(4);    //Unable to create database network

$Output = [];
$ResultCode = 0;

exec("docker run --name mariadb -d --network $DatabaseNetwork --network-alias $DatabaseNetworkAlias -e MYSQL_ROOT_PASSWORD=$DatabasePassword -e MYSQL_DATABASE=system -p 3306:3306 mariadb:latest", $Output, $ResultCode);

if(!empty($ResultCode))
    exit(5);    //Unable to run mariadb container

if($Mode != 'CI'){

    $Output = [];
    $ResultCode = 0;

    exec("docker run --name phpmyadmin -d --network $DatabaseNetwork -p 8080:80 -e PMA_HOST=$DatabaseNetworkAlias -e PMA_PORT=3306 phpmyadmin:latest", $Output, $ResultCode);

    if(!empty($ResultCode))
        exit(6);    //Unable to run phpmyadmin container

}

echo "Database:";

while (true) {

    echo '.';
    $Result = testDatabase(['Host' => $DatabaseHost, 'Port' => 3306, 'User' => 'root', 'Password' => $DatabasePassword]);

    sleep(1);

    if(!is_null($Result))
        break;

}

echo "\n";


if($Mode == 'DB_ONLY')
    exit(0);

$ServersList = [

    'kernel' => [
        'Host' => $DatabaseHost,
        'Port' => 3306,
        'HasSSL' => false,
    ],

    'common-information' => [
        'Host' => $DatabaseHost,
        'Port' => 3306,
        'HasSSL' => false,
    ],

    'customers-server-1' => [
        'Host' => $DatabaseHost,
        'Port' => 3306,
        'HasSSL' => false,
    ],
];

$DatabaseCredentials = ['User' => 'root', 'Password' => $DatabasePassword];

$Output = [];
$ResultCode = 0;

exec("openssl rand -base64 32", $Output, $ResultCode); //Creating a cryptographically strong random key

if(!empty($ResultCode) || !isset($Output[0]))
    exit(7);    //Unable to generate random key

try{

    $SharedMemory = new SharedMemory(KeysConstants::getServersList());
    $SharedMemory->write($ServersList);

} catch (Exception $Ex) {

    exit(8); //Unable to load database credentials

}

try{

    $SharedMemory = new SharedMemory(KeysConstants::getDatabaseCredentials());
    $SharedMemory->write($DatabaseCredentials);

} catch (Exception $Ex) {

    exit(9);   //Unable to load servers list

}

$JwtCredentials = ['Key' => trim($Output[0])];

try{

    $SharedMemory = new SharedMemory(KeysConstants::getJwtCredentials());
    $SharedMemory->write($JwtCredentials);

} catch (Exception $Ex) {

    exit(10);   //Unable to load jwt credentials

}

function testDatabase(array $Options) : ?int {

    $PDOOptions = [
        PDO::ATTR_PERSISTENT => false,
        PDO::ATTR_TIMEOUT => 1,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Make the default fetch be an associative array
    ];

    $Result = null;

    try {

        $DSN = 'mysql:host='.$Options['Host'].';port='.$Options['Port'].';charset=utf8';
        $PDO = new PDO($DSN, $Options['User'], $Options['Password'], $PDOOptions);
        $Sql = "SELECT 1 AS Result";
        $Stmt = $PDO->prepare($Sql);
        $Result = $Stmt->execute();

        if($Result && $Stmt->rowCount() > 0){
            $Result = $Stmt->fetch()['Result'];
        }

    } catch (Exception $Exception) {

        //this will generate an error anyway

    } finally {

        if(!empty($PDO)){

            try{
                $PDO->query('KILL CONNECTION_ID()');
            } catch (Exception $Exception){
                //this will generate an error anyway we only handle the error when killing the connection
            }

            $PDO = null;

        }

    }

    return $Result;

}
