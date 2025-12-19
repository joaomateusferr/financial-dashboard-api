<?php

namespace App\Helpers;

use \OpenSSLCertificate;

class CertificateHelper {

    public static function validateKeyPassword(string $Path, ?string $Password = null) : ?bool {

        if(!file_exists($Path))
            return null;

        $PrivateKey = openssl_pkey_get_private(file_get_contents($Path), $Password);

        if($PrivateKey !== false)
            return true;

        return false;

    }

    public static function isCertificateValid(OpenSSLCertificate $Certificate) : ?bool {

        if(empty($Certificate))
            return null;

        $CertificateData = openssl_x509_parse($Certificate);

        if(empty($CertificateData))
            return null;

        $Now = time();

        return ($Now >= $CertificateData['validFrom_time_t'] && $Now <= $CertificateData['validTo_time_t']);

    }

}