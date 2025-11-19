<?php

namespace TestDependencies\HTTP;

use PHPUnit\Framework\TestCase;

abstract class HTTPResponseTest extends TestCase {

    protected bool | string $Response;
    protected int $HttpCode;

    public function testResponseIsNotBool(): void {

        $this->assertFalse(is_bool($this->Response));

    }

    public function testResponseIsString(): void {

        $this->assertTrue(is_string($this->Response));

    }

    public function testResponseIsJson(): void {

        $this->assertTrue(json_validate($this->Response));

    }

    public function testResponseValidJsonDecode(): void {

        $this->assertTrue(is_array(json_decode($this->Response, true)));

    }

}