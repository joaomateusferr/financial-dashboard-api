<?php
namespace App\Services;

class Encryption {

    private const OpenSSLAlgorithm = 'aes-256-cbc';

    private static function getOpenSSLAlgorithm(): string {

        return self::OpenSSLAlgorithm;

    }

    public static function encrypt(string $Key, string $Data): string {

        $OpenSSLAlgorithm = self::getOpenSSLAlgorithm();
        $InitializationVector = openssl_random_pseudo_bytes(openssl_cipher_iv_length($OpenSSLAlgorithm));
        $EncryptedData = openssl_encrypt($Data, $OpenSSLAlgorithm, $Key, OPENSSL_RAW_DATA, $InitializationVector);
        return base64_encode($InitializationVector).'::'.base64_encode($EncryptedData);

    }

    public static function decrypt(string $Key, string $EncryptedData): string {

        if(empty($EncryptedData))
            return '';

        $Tokens = explode('::',$EncryptedData);

        if(empty($Tokens[1]))
            return '';

        $InitializationVector = base64_decode($Tokens[0]);
        $EncryptedData = base64_decode($Tokens[1]);

        $OpenSSLAlgorithm = self::getOpenSSLAlgorithm();
        $Data = openssl_decrypt($EncryptedData, $OpenSSLAlgorithm, $Key, OPENSSL_RAW_DATA, $InitializationVector);
        return $Data;

    }

}