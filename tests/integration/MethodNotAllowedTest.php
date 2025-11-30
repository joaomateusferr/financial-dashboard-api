<?php

declare(strict_types=1);

use TestDependencies\HTTP\HTTPResponseTest;

final class MethodNotAllowedTest extends HTTPResponseTest {

    public static function setUpBeforeClass(): void {

        $Curl = curl_init();
        $Url = "http://localhost:8888/ping";
        curl_setopt($Curl, CURLOPT_URL, $Url);
        curl_setopt($Curl, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($Curl, CURLOPT_POST, true);
        curl_setopt($Curl, CURLOPT_POSTFIELDS, json_encode([]));
        curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
        self::$Response = curl_exec($Curl);
        self::$HttpCode = curl_getinfo($Curl, CURLINFO_HTTP_CODE);

    }

    public function testResponseHttpCodeIs405(): void {

        $this->assertSame(self::$HttpCode, 405);

    }

    public function testResponseDefaultExpectedValues(): void {

        $ResponseArray = json_decode(self::$Response,true);
        $this->assertSame($ResponseArray, ['error' => true, 'message' => 'Method Not Allowed']);

    }

}
