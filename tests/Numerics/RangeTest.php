<?php

namespace DNW\Skills\Tests\Numerics;

use DNW\Skills\Numerics\Range;
use DNW\Skills\Tests\TestCase;
use Exception;

class RangeTest extends TestCase
{
    public function testConstructInvalidParam(): void
    {
        $this->expectException(Exception::class);
        $range = new Range(10, 5);
    }

    public function testFactoryInclusiveInvalidParam(): void
    {
        $this->expectException(Exception::class);
        $range = Range::inclusive(10, 5);
    }

    public function testNormalUse(): void
    {
        $range = Range::inclusive(1, 10);
        $this->assertEquals(1, $range->getMin());
        $this->assertEquals(10, $range->getMax());
    }
}
