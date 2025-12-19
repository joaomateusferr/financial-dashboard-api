<?php

namespace App\Services;

use \Exception;

class EmailContent {

    private string $Subject;
    private string $Body;
    private bool $ContainsHtml;
    private ?string $AltBody;

    public function __construct(string $Subject, string $Body, bool $ContainsHtml = false, ?string $AltBody = null) {

        $this->Subject = $Subject;
        $this->Body = $Body;

        if(!empty($ContainsHtml) && empty($AltBody))
            throw new Exception('When an email contains HTML, it is necessary to have an alternative body for email clients that do not support HTML.');

        $this->ContainsHtml = $ContainsHtml;
        $this->AltBody = $AltBody;

    }

    public function getSubject() : string {
        return $this->Subject;
    }

    public function getBody() : string {
        return $this->Body;
    }

    public function containsHtml() : bool {
        return $this->ContainsHtml;
    }

    public function getAltBody() : ?string {
        return $this->AltBody;
    }

}