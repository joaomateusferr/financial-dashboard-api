<?php
namespace App\Services;

class Password {

    public static function generatePasswordHash(string $Password): string {

        return password_hash($Password, PASSWORD_ARGON2ID);

    }

    public static function verifyPasswordHash(string $Password, string $Hash): bool {

        return password_verify($Password, $Hash);

    }

    public static function validateMinimumPasswordSecurity(string $Password): array {

        $Result = [];

        if (strlen($Password) < 8 || strlen($Password) > 50)
            $Result[] = 'The password must contain 8 to 50 characters!';

        if (!preg_match('/[A-Z]/', $Password))  //At least one capital letter
            $Result[] = 'The password must contain at least one capital letter!';

        if (!preg_match('/[\W_]/', $Password))    //At least one special character
            $Result[] = 'The password must contain at least one special character!';

        return $Result;

    }

}