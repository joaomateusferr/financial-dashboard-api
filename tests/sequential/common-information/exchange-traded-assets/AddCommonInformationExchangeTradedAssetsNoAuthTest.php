<?php

declare(strict_types=1);

use TestDependencies\HTTP\HTTPResponseTest;

final class AddCommonInformationExchangeTradedAssetsNoAuthTest extends HTTPResponseTest {

    public static function setUpBeforeClass(): void {

        $Curl = curl_init();
        $Url = self::getApiBase()."/common-information/exchange-traded-assets";
        curl_setopt($Curl, CURLOPT_URL, $Url);
        curl_setopt($Curl, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($Curl, CURLOPT_POST, true);
        curl_setopt($Curl, CURLOPT_POSTFIELDS, json_encode([["Ticker" => "HGLG", "AssetQualificationID" => 11, "ExchangeID" => 1, "AssetTypeID" => 11, "IsoCode" => "BRL"]]));
        curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
        self::$Response = curl_exec($Curl);
        self::$HttpCode = curl_getinfo($Curl, CURLINFO_HTTP_CODE);

    }

    public function testResponseHttpCodeIs401(): void {

        $this->assertSame(self::$HttpCode, 401);

    }

    public function testResponseDefaultExpectedMessage(): void {

        $ResponseArray = json_decode(self::$Response, true);
        $this->assertSame($ResponseArray['error'], true);
        $this->assertSame($ResponseArray['result'][0], 'Unauthorized');

    }

}
