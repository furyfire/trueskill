<?php

declare(strict_types=1);

namespace DNW\Skills\Tests;

use DNW\Skills\PartialPlay;
use DNW\Skills\Player;
use DNW\Skills\Guard;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(PartialPlay::class)]
#[UsesClass(Player::class)]
#[UsesClass(Guard::class)]
final class PartialPlayTest extends TestCase
{
    public function testgetPartialPlayPercentage(): void
    {
        $p = new Player(1, 0.5);
        $this->assertEquals($p->getPartialPlayPercentage(), PartialPlay::getPartialPlayPercentage($p));

        $p = new Player(1, 0.000000);
        $this->assertNotEquals(0.0, PartialPlay::getPartialPlayPercentage($p));
    }
}
