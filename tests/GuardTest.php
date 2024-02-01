<?php

namespace DNW\Skills\Tests;

use DNW\Skills\Guard;
use Exception;
use PHPUnit\Framework\TestCase;

class GuardTest extends TestCase
{
    public function testArgumentNotNull(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('dummy can not be null');
        Guard::argumentNotNull(null, "dummy");
    }

    public function testargumentIsValidIndex(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('dummy is an invalid index');
        Guard::argumentIsValidIndex(10, 10, "dummy");
    }

    public function testargumentIsValidIndex2(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('dummy is an invalid index');
        Guard::argumentIsValidIndex(-1, 10, "dummy");
    }

    public function testargumentInRangeInclusive(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('dummy is not in the valid range [0, 100]');
        Guard::argumentInRangeInclusive(101, 0, 100, "dummy");
    }
}
