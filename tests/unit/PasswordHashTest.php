<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Services\Password;

final class PasswordHashTest extends TestCase {

    private static string $Password;
    private static string $Hash;

    public static function setUpBeforeClass(): void {

        self::$Password = 'my-secret-password';
        self::$Hash = Password::generatePasswordHash(self::$Password);

    }

    public function testVerifyValidPasswordHash(): void {

        $this->assertTrue(Password::verifyPasswordHash(self::$Password, self::$Hash));

    }

    public function testVerifyInvalidPasswordHash(): void {

        $this->assertFalse(Password::verifyPasswordHash('not-my-secret-password', self::$Hash));

    }

    public function testRegeneratePasswordHash(): void {

        $this->assertNotSame(self::$Hash, Password::generatePasswordHash(self::$Password));

    }

}
