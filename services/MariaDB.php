<?php

class MariaDB {

    private $Server = '';
    Private $Database = '';
    Private $IP = '';
    Private $Port = 3306;
    Private $Connection = null;
    Private $Options = [
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', //Set binary encoding to UTF8
        PDO::ATTR_TIMEOUT => 30, // Set timeout to 30s
        PDO::ATTR_EMULATE_PREPARES => false, // Disable emulation mode for "real" prepared statements
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Disable errors in the form of exceptions
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Make the default fetch be an associative array
    ];


    public function __construct(string $Server, string $Database, array $Options = []) {

        $this->Server = $Server;
        $this->Database = $Database;

        if(!empty($Options))
            $this->Options = array_merge($this->Options, $Options); //Replace duplicates with $Options data

        $ServerInfo = IPMapping::get($this->Server);

        if(empty($ServerInfo))
            throw new Exception('Unable to get server info - '.$this->Server);

        $this->IP = $ServerInfo['IP'];
        $this->Port = $ServerInfo['Port'];

        if($ServerInfo['HasSSL']){
            $Options[PDO::MYSQL_ATTR_SSL_CA] = Credentials::getSSLCertificatePath();
            $Options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
        }

        $this->connect();

    }

    private function connect(){

        $DSN = "mysql:host=$this->IP;port=$this->Database;dbname=$this->Database;charset=utf8";
        $Credentials = Credentials::get();

        try {
            $this->PDO = new PDO($DSN, $Credentials['User'], $Credentials['Password'], $this->Options);
        } catch (Exception $Exception) {

            error_log($Exception->getMessage());
        }

    }

    public function close(){

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
