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

        try{

            $CustomersSystemConnection = new MariaDB('kernel', 'kernel');
            $Filter = ['Email' => $UserData['Email']];

            $Sql = 'SELECT ID FROM users WHERE Email = :Email LIMIT 1';
            $Stmt = $CustomersSystemConnection->prepare($Sql);
            $Result = $Stmt->execute($Filter);

            if($Result && $Stmt->rowCount() > 0)
                $AlreadyExists = true;

        } catch (Exception $Exception){

            return ['Code' => 500,'Message' => 'Unable to fetch data!'];

        }

        if($AlreadyExists)
            return ['Code' => 409,'Message' => 'User already registered!'];

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

        try{

            $Sql = 'INSERT INTO users (Email, PasswordHash, Type, ApiToken, CustomerServerID) VALUES (:Email, :PasswordHash, :Type, :ApiToken, :CustomerServerID)';
            $Stmt = $CustomersSystemConnection->prepare($Sql);
            $Result = $Stmt->execute($User);

            if($Result)
                return ['Code' => 200,'Message' => 'User created successfully!', 'ApiToken' => $User['ApiToken']];

        }catch (Exception $Exception){

            return ['Code' => 500,'Message' => 'Unable to update data!'];

        }

        return ['Code' => 500,'Message' => 'Invalid Result!'];

    }

    public static function login(array $UserData) : array {

        if(!isset($UserData['Email']))
            throw new Exception('Email field is mandatory!');

        if(!filter_var($UserData['Email'], FILTER_VALIDATE_EMAIL))
            throw new Exception('Invalid email!');

        if(!isset($UserData['Password']))
            throw new Exception('Password field is mandatory!');

        if(!isset($UserData['Authorization']))
            throw new Exception('Authorization field is mandatory!');


        try{

            $CustomersSystemConnection = new MariaDB('kernel', 'kernel');
            $Filter = ['Email' => $UserData['Email']];

            $Sql = 'SELECT Email, PasswordHash, ApiToken FROM users WHERE Email = :Email LIMIT 1';
            $Stmt = $CustomersSystemConnection->prepare($Sql);
            $Result = $Stmt->execute($Filter);

            $UserDetails= [];

            if($Result && $Stmt->rowCount() > 0)
                $UserDetails = $Stmt->fetch();

        } catch (Exception $Exception){

            return ['Code' => 500,'Message' => 'Unable to fetch data!'];

        }

        if(empty($UserDetails))
            return ['Code' => 401,'Message' => 'Invalid user!'];

        if($UserData['Authorization'] != $UserDetails['ApiToken'])
            return ['Code' => 401,'Message' => 'Invalid authorization!'];

        if(!Password::verifyPasswordHash($UserData['Password'], $UserDetails['PasswordHash']))
            return ['Code' => 401,'Message' => 'Invalid password!'];

        return ['Code' => 200,'Message' => 'Logged in successfully!', 'JWT' => 'jwt here'];

    }

}