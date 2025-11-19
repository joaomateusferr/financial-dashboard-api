<?php

$Parameters =  [
    'h' => [
        'Name' => 'Host',
        'Default' => 'exit',
    ],
    'g' => [
        'Name' => 'Port',
        'Default' => '3306',
    ],
    'u' => [
        'Name' => 'User',
        'Default' => 'exit',
    ],
    'p' => [
        'Name' => 'Password',
        'Default' => 'exit',
    ],
];

$Options = parseArguments($Parameters);

$PDOOptions = [
    PDO::ATTR_PERSISTENT => false,
    PDO::ATTR_TIMEOUT => 30,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Make the default fetch be an associative array
];


try {

    $Numbers = [rand(1, 10), rand(1, 10)];
    $DSN = 'mysql:host='.$Options['Host'].';port='.$Options['Port'].';charset=utf8';
    $PDO = new PDO($DSN, $Options['User'], $Options['Password'], $PDOOptions);
    $Sql = "SELECT ? + ? AS Sum";
    $Stmt = $PDO->prepare($Sql);
    $Result = $Stmt->execute($Numbers);

    if($Result && $Stmt->rowCount() > 0){
        echo $Numbers[0].' + '.$Numbers[1].' = ' . $Stmt->fetch()['Sum']."\n";
    }

} catch (Exception $Exception) {

    echo "Error: " . $Exception->getMessage()."\n";

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

function parseArguments(array $Parameters) : array {

    $Options = [];
    $Arguments = getopt(implode(':', array_keys($Parameters)).':');

    foreach ($Arguments as $Key => $Argument) {

        if(!is_array($Argument))
            $Options[$Parameters[$Key]['Name']] = $Arguments[$Key];
        else
            exit("This script does not support multiple entries of the same arguments\n");

    }

    foreach ($Parameters as $Key => $Parameter) {

        if(isset($Parameter['Default']) && !isset($Arguments[$Key])){

            if($Parameter['Default'] === 'exit')
                exit($Parameter['Name'].' ('.$Key.') is required!'."\n");
            else
                $Options[$Parameters[$Key]['Name']] = $Parameter['Default'];
        }

    }

    return $Options;

}