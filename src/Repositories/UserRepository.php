<?php

namespace App\Repositories;

use App\Services\MariaDB;
use App\Services\Password;
use App\Constants\ServersConstants;
use App\Constants\UsersConstants;
use \Exception;

class UserRepository {

    public static function create(string $Email, string $Password) : ?bool {

        $User = [
            'Email' => $Email,
            'PasswordHash' => Password::generatePasswordHash($Password),
            'Type' => UsersConstants::getDefaultType(),
            'CustomerServerID' => ServersConstants::getCurrentCustomerServerID(),
        ];

        try{

            $KernelConnection = new MariaDB('kernel', 'kernel');
            $Sql = 'INSERT INTO users (Email, PasswordHash, Type, CustomerServerID) VALUES (:Email, :PasswordHash, :Type, :CustomerServerID)';
            $Stmt = $KernelConnection->prepare($Sql);
            $Result = $Stmt->execute($User);
            return true;

        }catch (Exception $Exception){

            //add logs hererws
            return null;

        } finally {

            $KernelConnection->close();

        }

        return false;

    }

    public static function retrieveUserDetailsByEmail(string $Email) : ?array {


        $UserDetails = [];

        try{

            $KernelConnection = new MariaDB('kernel', 'kernel');
            $Filter = ['Email' => $Email];

            $Sql = 'SELECT ID, Type, Email, PasswordHash FROM users WHERE Email = :Email LIMIT 1';
            $Stmt = $KernelConnection->prepare($Sql);
            $Result = $Stmt->execute($Filter);

            if($Result && $Stmt->rowCount() > 0)
                $UserDetails = $Stmt->fetch();

        } catch (Exception $Exception){

           //add logs here
           return null;

        } finally {

            $KernelConnection->close();

        }

        return $UserDetails;

    }

    public static function changeUserType(int $ID, string $Type) : bool {

        if(!in_array($Type, UsersConstants::getSupportedTypes()))
            return false;

        try{

            $KernelConnection = new MariaDB('kernel', 'kernel');
            $Sql = 'UPDATE users SET Type = :Type WHERE ID = :ID';
            $Stmt = $KernelConnection->prepare($Sql);
            $Stmt->bindValue(':Type', $Type);
            $Stmt->bindValue(':ID', $ID);
            $Result = $Stmt->execute();

            if($Result)
                return true;

        }catch (Exception $Exception){

            //add logs hererws

        } finally {

            $KernelConnection->close();

        }

        return false;

    }

}