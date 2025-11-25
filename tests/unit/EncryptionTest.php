<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Services\Encryption;

final class EncryptionTest extends TestCase {

    private string $Key;
    private string $Data;
    private string $EncryptedData;

    protected function setUp() : void {

        $this->Key = 'my-secret-key';
        $this->Data = 'my secret phrase';
        $this->EncryptedData = Encryption::encrypt($this->Key, $this->Data);

    }

    public function testEncrypt(): void {

        $this->assertFalse(empty($this->EncryptedData));

    }

    public function testDecryptedDataNotEmpty(): void {

        $DecryptedData = Encryption::decrypt($this->Key, $this->EncryptedData);
        $this->assertFalse(empty($DecryptedData));

    }

    public function testDecryptedDataValue(): void {

        $DecryptedData = Encryption::decrypt($this->Key, $this->EncryptedData);
        $this->assertSame($DecryptedData, $this->Data);

    }

    public function testDecryptionWrongKey(): void {

        $DecryptedData = Encryption::decrypt('not-my-secret-key', $this->EncryptedData);
        $this->assertNotSame($DecryptedData, $this->Data);
        $this->assertTrue(empty($DecryptedData));

    }

}
