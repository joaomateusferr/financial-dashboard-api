<?php

declare(strict_types=1);

use TestDependencies\HTTP\HTTPResponseTest;
use App\Repositories\SessionRepository;
use App\Repositories\UserRepository;
use App\Helpers\SessionHelper;


final class LogoutRegisteredUserTest extends HTTPResponseTest {

    protected static array $Cookies;

    public static function setUpBeforeClass(): void {

        $TestEmail = 'joao.ferreira@gmail.com';
        $UserDetails = UserRepository::retrieveUserDetailsByEmail($TestEmail);

        if(empty($UserDetails))
            exit(1);

        $SessionToken = SessionRepository::getNewestActiveSessionTokenfromUser($UserDetails['ID']);

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

    public function testSessionDuration(): void {

        if(isset(self::$Cookies['sid'])){

            $Session = SessionRepository::get(self::$Cookies['sid'],['CreatedAt','ExpiresAt']);

            if(!empty($Session)){

                $Duration = $Session['ExpiresAt'] - $Session['CreatedAt'];
                $this->assertSame(SessionHelper::getStandardDuration(), $Duration);

            }

        }

    }

}
