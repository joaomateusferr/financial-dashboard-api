<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class PingTest extends TestCase {

    private $Response;
    private int $HttpCode;

    protected function setUp() : void {

        $Curl = curl_init();
        $Url = "http://localhost:8888/ping";
        curl_setopt($Curl, CURLOPT_URL, $Url);
        curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
        $this->Response = curl_exec($Curl);
        $this->HttpCode = curl_getinfo($Curl, CURLINFO_HTTP_CODE);
        curl_close($Curl);

    }

    public function testResponseIsString(): void {

        $this->assertTrue(is_string($this->Response));

    }

    public function testResponseIsNotBool(): void {

        $this->assertFalse(is_bool($this->Response));

    }

    public function testResponseHttpCodeIs200(): void {

        $this->assertSame($this->HttpCode, 200);

    }

    public function testResponseIsJson(): void {

        $this->assertTrue(json_validate($this->Response));

    }

    public function testResponseJsonDecode(): void {

        $this->assertTrue(is_array(json_decode($this->Response,true)));

    }

    public function testResponseValues(): void {

        $ResponseArray = json_decode($this->Response,true);
        $this->assertSame($ResponseArray, ['result' => ['pong']]);

    }

}
