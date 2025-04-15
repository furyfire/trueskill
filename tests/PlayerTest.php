<?php

declare(strict_types=1);

namespace DNW\Skills\Tests;

use DNW\Skills\Player;
use DNW\Skills\Guard;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(Player::class)]
#[UsesClass(Guard::class)]
final class PlayerTest extends TestCase
{
    public function testPlayerObjectGetterSetter(): void
    {
        $p = new Player('dummy', 0.1, 0.2);
        $this->assertEquals('dummy', $p->getId());
        $this->assertEquals(0.1, $p->getPartialPlayPercentage());
        $this->assertEquals(0.2, $p->getPartialUpdatePercentage());
    }
}
