<?php

namespace App\Services;

use \PDO;
use \Exception;
use App\Helpers\ServerHelper;

class MariaDB {

    private string $Server;
    private ?string $Database;
    private string $Host;
    private int $Port = 3306;
    private ?PDO $PDO = null;
    private array $Options = [
        PDO::ATTR_TIMEOUT => 30, // Set timeout to 30s
        PDO::ATTR_EMULATE_PREPARES => false, // Disable emulation mode for "real" prepared statements
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Disable errors in the form of exceptions
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Make the default fetch be an associative array
    ];


    public function __construct(string $Server, ?string $Database = null, array $Options = []) {

        $this->Server = $Server;
        $this->Database = $Database;

        if(!empty($Options))
            $this->Options = array_merge($this->Options, $Options); //Replace duplicates with $Options data

        $ServersInfo = ServerHelper::getDatabaseInfo($Server);
        $this->Host = $ServersInfo['Host'];
        $this->Port = $ServersInfo['Port'];

        $this->connect();

    }

    private function connect() : void {

        $DatabaseName = !empty($this->Database) ? "dbname=$this->Database;" : "";
        $DSN = "mysql:host=$this->Host;port=$this->Port;".$DatabaseName."charset=utf8";
        $Credentials = ServerHelper::getDatabaseCredentials();

        try {
            $this->PDO = new PDO($DSN, $Credentials['User'], $Credentials['Password'], $this->Options);
        } catch (Exception $Exception) {
            error_log($Exception->getMessage());
        }

    }

    public function close() : void {

        try{
            $this->PDO->query('KILL CONNECTION_ID()');
        } catch (Exception $Exception){
            //this will generate an error anyway we only handle the error when killing the connection
        }

        $this->PDO = null;

    }

    public function prepare(string $Sql){
        return $this->PDO->prepare($Sql);
    }

    public function lastInsertId() : int {
        return $this->PDO->lastInsertId();
    }

    public function connected() : bool {
        return !is_null($this->PDO);
    }

}