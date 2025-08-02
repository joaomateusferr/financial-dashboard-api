<?php
namespace App\Services;

class Password {

    public static function generatePasswordHash(string $Password): string {

        return password_hash($Password, PASSWORD_ARGON2ID);

    }

    public static function verifyPasswordHash(string $Password, string $Hash): bool {

        return password_verify($Password, $Hash);

    }

    public static function validateMinimumPasswordSecurity(string $Password): ?string {

        if (strlen($Password) < 8 || strlen($Password) > 50)
            return 'The password must contain 8 to 50 characters!';

        if (!preg_match('/^[A-Za-z0-9\W_]+$/', $Password))  //Only contains valid letters, numbers and special characters
            return 'Only letters, numbers and special characters are allowed in the password!';

        if (!preg_match('/[A-Z]/', $Password))  //At least one capital letter
            return 'The password must contain at least one capital letter!';

        if (!preg_match('/[\W_]/', $Password))    //At least one special character
            return 'The password must contain at least one special character!';

        return null;

    }

}