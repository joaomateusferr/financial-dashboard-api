<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Services\MariaDB;

try{

    $KernelConnection = new MariaDB('kernel');

    $Queries = [
        "CREATE DATABASE kernel",
        "USE kernel",
        "CREATE TABLE users (
            ID SERIAL PRIMARY KEY,
            Name VARCHAR(255),
            Email VARCHAR(255) NOT NULL,
            PasswordHash VARCHAR(255) NOT NULL,
            Type ENUM('STANDARD','ADMIN') NOT NULL DEFAULT 'STANDARD',
            ApiToken VARCHAR(64) NOT NULL,
            CustomerServerID INT NOT NULL
        )",
        "CREATE INDEX Email ON users (Email)"
    ];

    foreach($Queries as $Sql){

        $Stmt = $KernelConnection->prepare($Sql);
        $Result = $Stmt->execute();

    }

} catch (Exception $Exception) {

    echo $Exception->getMessage()."\n";

} finally {

    $KernelConnection->close();

}