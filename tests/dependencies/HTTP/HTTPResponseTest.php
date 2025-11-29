<?php

namespace TestDependencies\HTTP;

use PHPUnit\Framework\TestCase;

abstract class HTTPResponseTest extends TestCase {

    protected static bool | string $Response;
    protected static int $HttpCode;

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