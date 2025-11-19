<?php

$Mode = isset($argv[1]) ? $argv[1] : 'CI';

$DatabaseNetwork = 'network-db';
$DatabaseNetworkAlias = 'db';

$Output = [];
$ResultCode = 0;

exec("openssl rand -base64 45 | tr -dc 'A-Za-z0-9-().!@?#,/;+' | head -c30", $Output, $ResultCode); //Creating a cryptographically strong random password

if(!empty($ResultCode) || !isset($Output[0]))
    exit(1);    //Unable to generate database password

$DatabasePassword = trim($Output[0]);

$Output = [];
$ResultCode = 0;

exec("hostname -I | awk '{print $2}'", $Output, $ResultCode);

if(!empty($ResultCode) || !isset($Output[0]))
    exit(2);    //Unable to get database host

$DatabaseHost = empty($Output[0]) ? $DatabaseNetworkAlias : trim($Output[0]);

$Output = [];
$ResultCode = 0;

exec("docker network create $DatabaseNetwork", $Output, $ResultCode);

if(!empty($ResultCode))
    exit(3);    //Unable to create database network

$Output = [];
$ResultCode = 0;

exec("docker run --name mariadb -d --network $DatabaseNetwork --network-alias $DatabaseNetworkAlias -e MYSQL_ROOT_PASSWORD=$DatabasePassword -e MYSQL_DATABASE=system -p 3306:3306 mariadb:latest", $Output, $ResultCode);

if(!empty($ResultCode))
    exit(4);    //Unable to run mariadb container
