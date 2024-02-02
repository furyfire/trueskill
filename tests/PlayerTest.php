<?php declare(strict_types=1);

namespace DNW\Skills\Tests;

use DNW\Skills\Player;
use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase
{
    public function test(): void
    {
        $p = new Player('dummy', 0.1, 0.2);
        $this->assertEquals('dummy', (string)$p);
        $this->assertEquals(0.1, $p->getPartialPlayPercentage());
        $this->assertEquals(0.2, $p->getPartialUpdatePercentage());
    }
}
