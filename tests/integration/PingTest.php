<?php

declare(strict_types=1);

use TestDependencies\HTTP\HTTPResponseTest;

final class PingTest extends HTTPResponseTest {

    public static function setUpBeforeClass() : void {

        $Curl = curl_init();
        $Url = "http://localhost:8888/ping";
        curl_setopt($Curl, CURLOPT_URL, $Url);
        curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
        self::$Response = curl_exec($Curl);
        self::$HttpCode = curl_getinfo($Curl, CURLINFO_HTTP_CODE);

    }

    public function testResponseHttpCodeIs200(): void {

        $this->assertSame(self::$HttpCode, 200);

    }

    public function testResponseDefaultExpectedValues(): void {

        $ResponseArray = json_decode(self::$Response,true);
        $this->assertSame($ResponseArray, ['result' => ['pong']]);

    }

}
