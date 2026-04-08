<?php

declare(strict_types=1);

use TestDependencies\HTTP\HTTPResponseTest;

final class GetCommonInformationAssetQualificationInvalidIdentifierOnlyTest extends HTTPResponseTest {

    public static function setUpBeforeClass(): void {

        $Curl = curl_init();
        $Url = self::getApiBase()."/common-information/asset-qualification";
        curl_setopt($Curl, CURLOPT_URL, $Url);
        curl_setopt($Curl, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($Curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($Curl, CURLOPT_POSTFIELDS, json_encode([99]));
        curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
        self::$Response = curl_exec($Curl);
        self::$HttpCode = curl_getinfo($Curl, CURLINFO_HTTP_CODE);

    }

    public function testResponseHttpCodeIs200(): void {

        $this->assertSame(self::$HttpCode, 200);

    }

    public function testResponseDefaultExpectedValues(): void {

        $ResponseArray = json_decode(self::$Response,true);
        $this->assertTrue(empty($ResponseArray['result']));

    }

}
