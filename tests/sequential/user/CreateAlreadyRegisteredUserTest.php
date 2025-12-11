<?php

declare(strict_types=1);

use TestDependencies\HTTP\HTTPResponseTest;

final class CreateAlreadyRegisteredUserTest extends HTTPResponseTest {

    public static function setUpBeforeClass(): void {

        $Curl = curl_init();
        $Url = "http://localhost:8888//user/create";
        curl_setopt($Curl, CURLOPT_URL, $Url);
        curl_setopt($Curl, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($Curl, CURLOPT_POST, true);
        curl_setopt($Curl, CURLOPT_POSTFIELDS, json_encode(['Email' => 'joao.ferreira@gmail.com', 'Password' => 'Test1401!']));
        curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
        self::$Response = curl_exec($Curl);
        self::$HttpCode = curl_getinfo($Curl, CURLINFO_HTTP_CODE);

    }

    public function testResponseHttpCodeIs409(): void {

        $this->assertSame(self::$HttpCode, 409);

    }

    public function testResponseDefaultExpectedMessage(): void {

        $ResponseArray = json_decode(self::$Response,true);
        $this->assertSame($ResponseArray['error'], true);
        $this->assertSame($ResponseArray['result'][0], 'User already registered!');

    }

}
