<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Services\SharedMemory;

final class SharedMemoryTest extends TestCase {

    private string $IDString;
    private string $Key;
    private string $Value;
    private SharedMemory $SharedMemory;

    protected function setUp() : void {

        $this->IDString = 'test';
        $this->Key = 'key';
        $this->Value = 'value';
        $this->SharedMemory = new SharedMemory($this->IDString);
        $this->SharedMemory->write([$this->Key => $this->Value]);

    }

    public function testSharedMemoryWrite(): void {

        $Result = $this->SharedMemory->read();
        $this->assertTrue(is_array($Result));

    }

    public function testSharedMemoryResultKey(): void {

        $Result = $this->SharedMemory->read();
        $this->assertTrue(isset($Result[$this->Key]));

    }

    public function testSharedMemoryResultValue(): void {

        $Result = $this->SharedMemory->read();
        $this->assertSame($Result[$this->Key], $this->Value);

    }

}
