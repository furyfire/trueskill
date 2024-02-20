<?php

declare(strict_types=1);

namespace DNW\Skills\Tests\Numerics;

use DNW\Skills\Numerics\BasicMath;
use PHPUnit\Framework\TestCase;

class BasicMathTest extends TestCase
{
    public function testSquare(): void
    {
        $this->assertEquals(1, BasicMath::square(1));
        $this->assertEquals(1.44, BasicMath::square(1.2));
        $this->assertEquals(4, BasicMath::square(2));
    }

    public function testSum(): void
    {
        $arr = [1, 1, 1, 1];

        $func_return = fn(float $f): float => $f;
        $func_double = fn(float $f): float => $f * 2;
        $this->assertEquals(4, BasicMath::sum($arr, $func_return));
        $this->assertEquals(8, BasicMath::sum($arr, $func_double));
    }
}
