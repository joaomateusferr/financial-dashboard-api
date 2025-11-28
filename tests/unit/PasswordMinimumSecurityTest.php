<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use App\Services\Password;

final class PasswordMinimumSecurityTest extends TestCase {

    public static function passwordProvider(): array{

        return[
            'The password must contain at least 8 characters' => ['pass', 'The password must contain 8 to 50 characters!'],
            'The password must be less than 51 characters' => ['myreallyreallyreallyreallyreallyreallyreallylongpassword', 'The password must contain 8 to 50 characters!'],
            'The password must contain at least one capital letter' => ['mypassword', 'The password must contain at least one capital letter!'],
            'The password must contain at least one special character' => ['Mypassword', 'The password must contain at least one special character!'],
            'The password meets all minimum criteria' => ['Mypassword!', null],
        ];

    }

    #[DataProvider('passwordProvider')]
    public function testMinimumSecurityPassword(string $Password, ?string $ExpectedValue): void {

        $this->assertSame(Password::validateMinimumPasswordSecurity($Password), $ExpectedValue);

    }

}
