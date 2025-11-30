<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Services\Encryption;

final class EncryptionTest extends TestCase {

    private static string $Key;
    private static string $Data;
    private static string $EncryptedData;

    public static function setUpBeforeClass(): void {

        self::$Key = 'my-secret-key';
        self::$Data = 'my secret phrase';
        self::$EncryptedData = Encryption::encrypt(self::$Key, self::$Data);

    }

    public function testEncrypt(): void {

        $this->assertFalse(empty(self::$EncryptedData));

    }

    public function testDecryptedDataNotEmpty(): void {

        $DecryptedData = Encryption::decrypt(self::$Key, self::$EncryptedData);
        $this->assertFalse(empty($DecryptedData));

    }

    public function testDecryptedDataValue(): void {

        $DecryptedData = Encryption::decrypt(self::$Key, self::$EncryptedData);
        $this->assertSame($DecryptedData, self::$Data);

    }

    public function testDecryptionWrongKey(): void {

        $DecryptedData = Encryption::decrypt('not-my-secret-key', self::$EncryptedData);
        $this->assertNotSame($DecryptedData, self::$Data);
        $this->assertTrue(empty($DecryptedData));

    }

}
