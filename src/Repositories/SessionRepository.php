<?php

namespace App\Repositories;

use App\Services\MariaDB;
use \Exception;

class SessionRepository {

    private static function generateToken() : string {

        return bin2hex(random_bytes(32));

    }

    private static function getStandardDuration() : int {

        return 86400;   //a day

    }

    public static function get(string $Token, array $Fields = []) : array {

        try{

            $KernelConnection = new MariaDB('kernel', 'kernel');
            $Filter = ['Token' => $Token];

            $FieldsString = '*';

            if(!empty($Fields))
                $FieldsString = implode(', ', $Fields);

            $Sql = 'SELECT '.$FieldsString.' FROM sessions WHERE Token = :Token';
            $Stmt = $KernelConnection->prepare($Sql);
            $Result = $Stmt->execute($Filter);

            $SessionDetails = [];

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
                'Token' =>self::generateToken(),
                'UserID' => $UserID,
                'UserAgent' => $UserAgent,
                'ExpiresAt' => time() + self::getStandardDuration()
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

}