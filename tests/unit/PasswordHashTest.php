<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Services\Password;

final class PasswordHashTest extends TestCase {

    private string $Password;
    private string $Hash;

    protected function setUp() : void {

        $this->Password = 'my-secret-password';
        $this->Hash = Password::generatePasswordHash($this->Password);

    }

    public function testVerifyValidPasswordHash(): void {

        $this->assertTrue(Password::verifyPasswordHash($this->Password, $this->Hash));

    }

    public function testVerifyInvalidPasswordHash(): void {

        $this->assertFalse(Password::verifyPasswordHash('not-my-secret-password', $this->Hash));

    }

    public function testRegeneratePasswordHash(): void {

        $this->assertNotSame($this->Hash, Password::generatePasswordHash($this->Password));

    }

}
