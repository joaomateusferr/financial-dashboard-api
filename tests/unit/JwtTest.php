<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Helpers\JwtHelper;

final class JwtTest extends TestCase {

    private static int $UserID;
    private static string $Type;
    private static ?string $Jwt;

    public static function setUpBeforeClass(): void {

        self::$UserID = 10;
        self::$Type = 'ADMIN';
        self::$Jwt = JwtHelper::create(self::$UserID, self::$Type);

    }

    public function testJwtCreation(): void {

        $this->assertNotNull(self::$Jwt);

    }

    public function testJwtCreationUserDataType(): void {

        $Result = JwtHelper::parse(self::$Jwt);
        $this->assertSame($Result['Data']['type'], self::$Type);

    }

    public function testJwtCreationUserDataUserId(): void {

        $Result = JwtHelper::parse(self::$Jwt);
        $this->assertSame($Result['Data']['id'], self::$UserID);

    }

    public function testJwtAlgorithm(): void {

        $Result = JwtHelper::parse(self::$Jwt);
        $this->assertSame($Result['Headers']['alg'], 'HS256');

    }

}
