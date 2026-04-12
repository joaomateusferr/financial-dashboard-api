<?php

namespace TestDependencies\HTTP;

use PHPUnit\Framework\TestCase;

abstract class HTTPResponseTest extends TestCase {

    protected static bool | string $Response;
    protected static int $HttpCode;

    protected static function getApiBase(): string {

        return 'http://localhost:8888';

    }

    public static function login(string $User, string $Password) : ?string {

        $Cookie = null;
        $Options = [ 'http' => ['user_agent' => 'script','header'  => "Content-type: application/json",'method'  => 'POST', 'content' => json_encode(['Email' => $User, 'Password' => $Password])]];
        $Result = @file_get_contents(self::getApiBase().'/session', false, stream_context_create($Options));

        if(!empty($Result)){

            foreach($http_response_header as $ResponseHeaderLine){

                if (preg_match('/^Set-Cookie:\s*([^;]+)/i', $ResponseHeaderLine, $Matches)) {

                    $Cookie = $Matches[1];
                    break;

                }

            }

        }

        return $Cookie;

    }

    public static function logout(string $Cookie) : bool {

        $Options = [ 'http' => ['header'  => "Cookie: $Cookie\r\n",'method'  => 'DELETE']];
        $Result = @file_get_contents(self::getApiBase().'/session', false, stream_context_create($Options));

        if(!empty($Result))
            return true;

        return false;

    }

    public function testResponseIsNotBool(): void {

        $this->assertFalse(is_bool(self::$Response));

    }

    public function testResponseIsString(): void {

        $this->assertTrue(is_string(self::$Response));

    }

    public function testResponseIsJson(): void {

        $this->assertTrue(json_validate(self::$Response));

    }

    public function testResponseValidJsonDecode(): void {

        $this->assertTrue(is_array(json_decode(self::$Response, true)));

    }

}