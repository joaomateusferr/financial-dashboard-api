<?php

declare(strict_types=1);

use TestDependencies\HTTP\HTTPResponseTest;

final class GetCommonInformationAssetQualificationValidIdentifierOnlyTest extends HTTPResponseTest {

    public static function setUpBeforeClass(): void {

        $Curl = curl_init();
        $Url = "http://localhost:8888/common-information/asset-qualification";
        curl_setopt($Curl, CURLOPT_URL, $Url);
        curl_setopt($Curl, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($Curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($Curl, CURLOPT_POSTFIELDS, json_encode([11]));
        curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
        self::$Response = curl_exec($Curl);
        self::$HttpCode = curl_getinfo($Curl, CURLINFO_HTTP_CODE);

    }

    public function testResponseHttpCodeIs200(): void {

        $this->assertSame(self::$HttpCode, 200);

    }

    public function testResponseDefaultExpectedValues(): void {

        $ResponseArray = json_decode(self::$Response,true);
        $this->assertFalse(isset($ResponseArray['error']));
        $this->assertTrue(isset($ResponseArray['result'][11]));

    }

}
