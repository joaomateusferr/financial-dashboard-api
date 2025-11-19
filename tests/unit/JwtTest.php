<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Helpers\JwtHelper;

final class JwtTest extends TestCase {

    private int $UserID;
    private string $Type;
    private ?string $Jwt;

    protected function setUp() : void {

        $this->UserID = 10;
        $this->Type = 'ADMIN';
        $this->Jwt = JwtHelper::create($this->UserID, $this->Type);

    }

    public function testJwtCreation(): void {

        $this->assertNotNull($this->Jwt);

    }

    public function testJwtCreationUserDataType(): void {

        $Result = JwtHelper::parse($this->Jwt);
        $this->assertSame($Result['Data']['type'], $this->Type);

    }

    public function testJwtCreationUserDataUserId(): void {

        $Result = JwtHelper::parse($this->Jwt);
        $this->assertSame($Result['Data']['id'], $this->UserID);

    }

    public function testJwtAlgorithm(): void {

        $Result = JwtHelper::parse($this->Jwt);
        $this->assertSame($Result['Headers']['alg'], 'HS256');

    }

}
