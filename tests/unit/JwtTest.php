<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Helpers\JwtHelper;

final class JwtTest extends TestCase {

    public function testJwtCreationUserDataID(): void {

        $UserID = 10;
        $Type = 'ADMIN';
        $Jwt = JwtHelper::create($UserID, $Type);
        $Result = JwtHelper::parse($Jwt);

        $this->assertSame($Result['Data']['id'], $UserID);

    }

    public function testJwtCreationUserDataType(): void {

        $UserID = 10;
        $Type = 'ADMIN';
        $Jwt = JwtHelper::create($UserID, $Type);
        $Result = JwtHelper::parse($Jwt);

        $this->assertSame($Result['Data']['type'], $Type);

    }

    public function testJwtAlgorithm(): void {

        $UserID = 10;
        $Type = 'ADMIN';
        $Jwt = JwtHelper::create($UserID, $Type);
        $Result = JwtHelper::parse($Jwt);

        $this->assertSame($Result['Headers']['alg'], 'HS256');

    }

}
