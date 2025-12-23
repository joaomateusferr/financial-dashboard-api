<?php

declare(strict_types=1);

use TestDependencies\HTTP\HTTPResponseTest;
use App\Repositories\SessionRepository;
use App\Repositories\UserRepository;

final class LogoutRegisteredUserTest extends HTTPResponseTest {

    protected static array $Cookies;

    public static function setUpBeforeClass(): void {

        $TestEmail = 'joao.ferreira@gmail.com';
        $UserDetails = UserRepository::retrieveUserDetailsByEmail($TestEmail);

        if(empty($UserDetails))
            throw new Exception("Required user details are missing.");

        $SessionToken = SessionRepository::getNewestActiveSessionTokenFromUser($UserDetails['ID']);

        if(empty($SessionToken))
            throw new Exception("Required session token are missing.");

        $Curl = curl_init();
        $Url = "http://localhost:8888/session";
        curl_setopt($Curl, CURLOPT_URL, $Url);
        curl_setopt($Curl, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($Curl, CURLOPT_COOKIE, "sid=$SessionToken");
        curl_setopt($Curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($Curl, CURLOPT_HEADER, true);
        $Response = curl_exec($Curl);
        self::$HttpCode = curl_getinfo($Curl, CURLINFO_HTTP_CODE);
        $HeaderSize = curl_getinfo($Curl, CURLINFO_HEADER_SIZE);

        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $Response,  $MatchFound);

        $Cookies = [];

        foreach($MatchFound[1] as $Item) {

            parse_str($Item,  $Cookie);
            $Cookies = array_merge($Cookies,  $Cookie);

        }

        self::$Cookies = $Cookies;
        self::$Response = substr($Response, $HeaderSize);

    }

    public function testResponseHttpCodeIs200(): void {

        $this->assertSame(self::$HttpCode, 200);

    }

    public function testResponseDefaultExpectedMessage(): void {

        $ResponseArray = json_decode(self::$Response,true);
        $this->assertFalse(isset($ResponseArray['error']));
        $this->assertSame($ResponseArray['result']['0'], 'Logout successfully!');

    }

    public function testSessionIdExistence(): void {

        $this->assertTrue(isset(self::$Cookies['sid']));

    }

    public function testCookieSidDeleted(): void {

        if(!isset(self::$Cookies['sid']))
            $this->fail("Required session id is missing.");

        $this->assertSame(self::$Cookies['sid'], 'deleted');


    }

}
