<?php

namespace App\Services;

use \PDO;
use \Exception;
use App\Constants\KeysConstants;

class MariaDB {

    private string $Server;
    private ?string $Database;
    private string $Host;
    private int $Port = 3306;
    private $PDO = null;
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

        $SharedMemory = new SharedMemory(KeysConstants::getServersList());
        $ServersInfo = $SharedMemory->read();

        if(empty($ServersInfo))
            throw new Exception('Unable to get servers info');

        if(empty($ServersInfo[$this->Server]))
            throw new Exception('Unable to get server info - '.$this->Server);

        if(empty($ServersInfo[$this->Server]['Host']))
            throw new Exception('Empty Host - '.$this->Server);

        if(empty($ServersInfo[$this->Server]['Port']))
            throw new Exception('Empty Port - '.$this->Server);

        $this->Host = $ServersInfo[$this->Server]['Host'];
        $this->Port = $ServersInfo[$this->Server]['Port'];

        if($ServersInfo[$this->Server]['HasSSL']){
            $Options[PDO::MYSQL_ATTR_SSL_CA] = $ServersInfo[$this->Server]['SSLCertificatePath'];
            $Options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
        }

        $this->connect();

    }

    private function connect() : void {

        $DatabaseName = !empty($this->Database) ? "dbname=$this->Database;" : "";
        $DSN = "mysql:host=$this->Host;port=$this->Port;".$DatabaseName."charset=utf8";
        $SharedMemory = new SharedMemory(KeysConstants::getDatabaseCredentials());
        $Credentials = $SharedMemory->read();

        if(empty($Credentials['User']))
            throw new Exception("Empty Database User");

        if(empty($Credentials['Password']))
            throw new Exception("Empty Database Password");

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

}