<?php

namespace App\Repositories;

use App\Services\MariaDB;
use App\Helpers\SessionHelper;
use \Exception;

class SessionRepository {

    public static function getNewestActiveSessionTokenFromUser(int $UserID) : ?string {

        try{

            $KernelConnection = new MariaDB('kernel', 'kernel');
            $Filter = ['UserID' => $UserID, 'Time' => time()];

            $Sql = 'SELECT Token FROM sessions WHERE UserID = :UserID AND ExpiresAt > :Time ORDER BY ExpiresAt DESC LIMIT 1';
            $Stmt = $KernelConnection->prepare($Sql);
            $Result = $Stmt->execute($Filter);

            $SessionID = null;

            if($Result && $Stmt->rowCount() > 0)
                $SessionID = ($Stmt->fetch())['Token'];

            return $SessionID;

        } catch (Exception $Exception){

            //add logs here
            return null;

        } finally {

            $KernelConnection->close();

        }

    }

    public static function isAdmin (string $Token) : ?bool {

        try{

            $KernelConnection = new MariaDB('kernel', 'kernel');

            $SessionDetails = [];

            $Filter = ['Token' => $Token];
            $Sql = 'SELECT UserID FROM sessions WHERE Token = :Token';
            $Stmt = $KernelConnection->prepare($Sql);
            $Result = $Stmt->execute($Filter);

            if($Result && $Stmt->rowCount() > 0)
                $SessionDetails = $Stmt->fetch();

            if(empty($SessionDetails))
                return null;

            $Sql = 'SELECT Type FROM users WHERE ID = :UserID';
            $Stmt = $KernelConnection->prepare($Sql);
            $Result = $Stmt->execute($SessionDetails);

            if($Result && $Stmt->rowCount() > 0)
                $UserDetails = $Stmt->fetch();

            return $UserDetails['Type'] === 'ADMIN';

        } catch (Exception $Exception){

            //add logs here
            return null;

        } finally {

            $KernelConnection->close();

        }

    }

    public static function get(string $Token, array $Fields = []) : array {

        try{

            $KernelConnection = new MariaDB('kernel', 'kernel');
            $Filter = ['Token' => $Token];

            $FieldsString = '*';

            if(!empty($Fields))
                $FieldsString = implode(', ', $Fields);

            $SessionDetails = [];

            $Sql = 'SELECT '.$FieldsString.' FROM sessions WHERE Token = :Token';
            $Stmt = $KernelConnection->prepare($Sql);
            $Result = $Stmt->execute($Filter);

            if($Result && $Stmt->rowCount() > 0)
                $SessionDetails = $Stmt->fetch();

            return $SessionDetails;

        } catch (Exception $Exception){

            //add logs here
            return [];

        } finally {

            $KernelConnection->close();

        }

    }

    public static function set(int $UserID, string $UserAgent) : array {

        try{

            $Result = [];

            $Session = [
                'Token' => SessionHelper::generateToken(),
                'UserID' => $UserID,
                'UserAgent' => $UserAgent,
                'ExpiresAt' => time() + SessionHelper::getStandardDuration()
            ];

            $KernelConnection = new MariaDB('kernel', 'kernel');
            $Sql = 'INSERT INTO sessions (Token, UserID, UserAgent, ExpiresAt) VALUES (:Token, :UserID, :UserAgent, :ExpiresAt)';
            $Stmt = $KernelConnection->prepare($Sql);
            $Result = $Stmt->execute($Session);

            if($Result)
                $Result = ['SID' => $Session['Token'], 'ExpiresAt' => $Session['ExpiresAt']];

            return $Result;

        } catch (Exception $Exception) {

            //add logs here
            return [];

        } finally {

            $KernelConnection->close();

        }

    }

    public static function delete(string $Token) : bool {

        $Session = self::get($Token, ['ID']);

        if(!empty($Session)){

            try{

                $KernelConnection = new MariaDB('kernel', 'kernel');
                $Sql = 'DELETE FROM sessions WHERE ID = :ID';
                $Stmt = $KernelConnection->prepare($Sql);
                $Result = $Stmt->execute($Session);
                return (bool) $Result;

            } catch (Exception $Exception) {

                //add logs here
                return false;

            } finally {

                $KernelConnection->close();

            }

        }

        return true;

    }

}