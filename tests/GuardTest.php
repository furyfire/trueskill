<?php

namespace DNW\Skills\Tests;

use DNW\Skills\Guard;
use Exception;

class GuardTest extends TestCase
{
    public function testArgumentNotNull(): void
    {
        $this->expectException(Exception::class);
        Guard::argumentNotNull(null, "dummy");
    }

    public function testargumentIsValidIndex(): void
    {
        $this->expectException(Exception::class);
        Guard::argumentIsValidIndex(25, 10, "dummy");
    }


    public function testargumentInRangeInclusive(): void
    {
        $this->expectException(Exception::class);
        Guard::argumentInRangeInclusive(101, 0, 100, "dummy");
    }
}
