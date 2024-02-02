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
}
