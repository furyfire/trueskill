<?php

declare(strict_types=1);

namespace DNW\Skills\Tests;

use DNW\Skills\Guard;
use Exception;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Guard::class)]
final class GuardTest extends TestCase
{
    public function testargumentIsValidIndexArgumentAbove(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('dummy is an invalid index');
        Guard::argumentIsValidIndex(10, 10, "dummy");
    }

    public function testargumentIsValidIndexArgumentBelow(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('dummy is an invalid index');
        Guard::argumentIsValidIndex(-1, 10, "dummy");
    }

    public function testargumentIsValidIndexArgumentValid(): void
    {
        Guard::argumentIsValidIndex(0, 10, "dummy");
        Guard::argumentIsValidIndex(1, 10, "dummy");
        Guard::argumentIsValidIndex(9, 10, "dummy");
        $this->expectNotToPerformAssertions();
    }

    public function testargumentInRangeInclusiveAbove(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('dummy is not in the valid range [0, 100]');
        Guard::argumentInRangeInclusive(101, 0, 100, "dummy");
    }

    public function testargumentInRangeInclusiveBelow(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('dummy is not in the valid range [0, 100]');
        Guard::argumentInRangeInclusive(-1, 0, 100, "dummy");
    }

    public function testargumentInRangeInclusiveValid(): void
    {
        Guard::argumentInRangeInclusive(0, 0, 100, "dummy");
        Guard::argumentInRangeInclusive(1, 0, 100, "dummy");
        Guard::argumentInRangeInclusive(50, 0, 100, "dummy");
        Guard::argumentInRangeInclusive(99, 0, 100, "dummy");
        Guard::argumentInRangeInclusive(100, 0, 100, "dummy");

        $this->expectNotToPerformAssertions();
    }
}
