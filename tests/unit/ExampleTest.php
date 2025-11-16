<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ExampleTest extends TestCase {

    public function testTwoValuesAreTheSame(): void {

        $n1 = 1;
        $n2 = 1;

        $this->assertSame($n1, $n2);
    }

}
