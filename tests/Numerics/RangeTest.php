<?php

declare(strict_types=1);

namespace DNW\Skills\Tests\Numerics;

use DNW\Skills\Numerics\Range;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Exception;

#[CoversClass(Range::class)]
class RangeTest extends TestCase
{
    public function testConstructInvalidParam(): void
    {
        $this->expectException(Exception::class);
        new Range(10, 5);
    }

    public function testFactoryInclusiveInvalidParam(): void
    {
        $this->expectException(Exception::class);
        Range::inclusive(10, 5);
    }

    public function testNormalUse(): void
    {
        $range = Range::inclusive(1, 10);
        $this->assertEquals(1, $range->getMin());
        $this->assertEquals(10, $range->getMax());
        $this->assertEquals(FALSE, $range->isInRange(0));
        $this->assertEquals(TRUE, $range->isInRange(1));
        $this->assertEquals(TRUE, $range->isInRange(2));
        $this->assertEquals(TRUE, $range->isInRange(9));
        $this->assertEquals(TRUE, $range->isInRange(10));
        $this->assertEquals(FALSE, $range->isInRange(11));

        $range = Range::atLeast(20);
        $this->assertEquals(20, $range->getMin());
        $this->assertEquals(PHP_INT_MAX, $range->getMax());

        $range = Range::exactly(5);
        $this->assertEquals(5, $range->getMin());
        $this->assertEquals(5, $range->getMax());
    }
}
