<?php

namespace App\Services;

use \Exception;

class EmailProvider {

    private string $Host;
    private string $Port;
    private bool $RequireAuthentication;
    private ?string $User;
    private ?string $Password;

    public function __construct(string $Host, int $Port, ?string $User = null, ?string $Password = null) {

        $this->Host = $Host;
        $this->Port = $Port;

        if(empty($User) && empty($Password)){

            $this->RequireAuthentication = false;
            $this->User = null;
            $this->Password = null;

        } elseif(empty($User)) {

            throw new Exception('The email provider password was provided, but the user was not.');

        } elseif(empty($Password)){

            throw new Exception('The email provider user was provided, but the password was not.');

        } else {

            $this->RequireAuthentication = true;
            $this->User = $User;
            $this->Password = $Password;

        }

    }

    public function getHost() : string {
        return $this->Host;
    }

    public function getPort() : int {
        return $this->Port;
    }

    public function requireAuthentication() : bool {
        return $this->RequireAuthentication;
    }

    public function getUser() : ?string {
        return $this->User;
    }

    public function getPassword() : ?string {
        return $this->Password;
    }

}