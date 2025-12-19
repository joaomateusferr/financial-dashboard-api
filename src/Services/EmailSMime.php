<?php

namespace App\Services;

use App\Helpers\CertificateHelper;
use \Exception;

class EmailSMime  {

    private string $CertificatePath;
    private string $KeyPath;
    private string $KeyPassword;

    public function __construct(string $CertificatePath, string $KeyPath, string $KeyPassword) {


        if(!file_exists($CertificatePath))
            throw new Exception('The certificate could not be found!');

        $this->CertificatePath = $CertificatePath;

        $Certificate = openssl_x509_read(file_get_contents($this->CertificatePath));

        if(empty(CertificateHelper::isCertificateValid($Certificate)))
            throw new Exception('The certificate is not valid!');

        if(!file_exists($KeyPath))
            throw new Exception('The key could not be found!');

        $this->KeyPath = $KeyPath;

        if(empty(CertificateHelper::validateKeyPassword($KeyPath, $KeyPassword)))
            throw new Exception('Invalid key password!');

        $this->KeyPassword = $KeyPassword;

    }

    public function getCertificatePath() : string {
        return $this->CertificatePath;
    }

    public function getKeyPath() : string {
        return $this->KeyPath;
    }

    public function getKeyPassword() : string {
        return $this->KeyPassword;
    }

}