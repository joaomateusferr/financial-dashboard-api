<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Repositories\UserRepository;
use App\Constants\UsersConstants;

$Port = '8888';
$Url = "http://localhost:$Port/user";

$DefaultRootCredentials = UsersConstants::getDefaultRootCredentials();
$Email = $DefaultRootCredentials['Email'];
$Password = $DefaultRootCredentials['Password'];

$UserDetails = UserRepository::retrieveUserDetailsByEmail($Email);

if(empty($UserDetails)){

    $Options = [ 'http' => ['user_agent' => 'script','header'  => "Content-type: application/json",'method'  => 'POST', 'content' => json_encode(['Email' => $Email, 'Password' => $Password])]];
    $Result = @file_get_contents($Url, false, stream_context_create($Options));

    if(empty($Result))
        exit("User creation failed!\n");

    $Result = json_decode($Result, true);
    $Result = $Result['result'][0];
    echo $Result."\n";

} else {

    echo "Default root user already created\n";

    if($UserDetails['Type'] == 'ADMIN')
        exit("Default root user already is admin!\n");

}

$UserDetails = UserRepository::retrieveUserDetailsByEmail($Email);
$Result = UserRepository::changeUserType($UserDetails['ID'],'ADMIN');

if(!$Result)
    exit("User type change failed!\n");

echo "Root now is Admin!\n";
