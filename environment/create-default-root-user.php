<?php

require dirname(__DIR__, 1).'/config.php';

use App\Repositories\UserRepository;
use App\Constants\UsersConstants;
use App\Helpers\DatabaseHelper;

$DefaultRootCredentials = UsersConstants::getDefaultRootCredentials();

DatabaseHelper::connect('kernel', 'kernel');

$UserDetails = UserRepository::retrieveUserDetailsByEmail($DefaultRootCredentials['Email']);

if(empty($UserDetails)){

    $Result = UserRepository::create($DefaultRootCredentials['Email'], $DefaultRootCredentials['Password']);

    if(empty($Result))
        exit("User creation failed!\n");

    echo "User created successfully!\n";

} else {

    echo "Default root user already created\n";

    if($UserDetails['Type'] == 'ADMIN')
        exit("Default root user already is admin!\n");

}

$UserDetails = UserRepository::retrieveUserDetailsByEmail($DefaultRootCredentials['Email']);
$Result = UserRepository::changeUserType($UserDetails['ID'],'ADMIN');

DatabaseHelper::disconnect('kernel');

if(!$Result)
    exit("User type change failed!\n");

echo "Root now is Admin!\n";
