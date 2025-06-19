<?php
namespace App\Services;

class Password {

    public static function generatePasswordHash(string $Password): string {

        return password_hash($Password, PASSWORD_ARGON2ID);

    }

    public static function verifyPasswordHash(string $Password, string $Hash): string {

        return password_verify($Password, $Hash);

    }

}