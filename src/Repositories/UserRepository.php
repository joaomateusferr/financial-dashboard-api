<?php

namespace App\Repositories;

use App\Services\MariaDB;
use App\Services\Password;
use App\Helpers\ApiHelper;
use App\Constants\ServersConstants;
use \Exception;

class UserRepository {

    private const DefaultType = 'STANDARD';

    private static function getDefaultType() {

        return self::DefaultType;

    }

    public static function create(array $UserData) : array {

        if(!isset($UserData['Email']))
            throw new Exception('Email field is mandatory!');

        if(!filter_var($UserData['Email'], FILTER_VALIDATE_EMAIL))
            throw new Exception('Invalid email!');

        if(!isset($UserData['Password']))
            throw new Exception('Password field is mandatory!');

        $AlreadyExists = false;

        $CustomersSystemConnection = new MariaDB('kernel', 'kernel');

        $Filter = ['Email' => $UserData['Email']];

        $Sql = 'SELECT ID FROM users WHERE Email = :Email LIMIT 1';
        $Stmt = $CustomersSystemConnection->prepare($Sql);
        $Result = $Stmt->execute($Filter);

        if($Result && $Stmt->rowCount() > 0)
           $AlreadyExists = true;

        if($AlreadyExists)
            throw new Exception('User already registered!');

        $PasswordResultString = Password::validateMinimumPasswordSecurity($UserData['Password']);

        if(!empty($PasswordResultString))
            throw new Exception($PasswordResultString);

        $User = [
            'Email' => $UserData['Email'],
            'PasswordHash' => Password::generatePasswordHash($UserData['Password']),
            'Type' => self::getDefaultType(),
            'ApiToken' => ApiHelper::generateToken(),
            'CustomerServerID' => ServersConstants::getCurrentCustomerServerID(),
        ];

        $Sql = 'INSERT INTO users (Email, PasswordHash, Type, ApiToken, CustomerServerID) VALUES (:Email, :PasswordHash, :Type, :ApiToken, :CustomerServerID)';
        $Stmt = $CustomersSystemConnection->prepare($Sql);
        $Result = $Stmt->execute($User);

        if($Result)
            return ['Code' => 200,'Message' => 'User created successfully!', 'ApiToken' => $User['ApiToken']];

        return ['Code' => 500,'Message' => 'Invalid Result!'];

    }

}