<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Repositories\UserRepository;
use App\Constants\UsersConstants;

$DefaultRootCredentials = UsersConstants::getDefaultRootCredentials();
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

if(!$Result)
    exit("User type change failed!\n");

echo "Root now is Admin!\n";
