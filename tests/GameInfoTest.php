<?php

declare(strict_types=1);

namespace DNW\Skills\Tests;

use DNW\Skills\GameInfo;
use DNW\Skills\Rating;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(GameInfo::class)]
#[UsesClass(Rating::class)]
final class GameInfoTest extends TestCase
{
    public function testMembers(): void
    {
        $gi = new GameInfo(1, 2, 3, 4, 5);
        $this->assertEquals(1, $gi->getInitialMean());
        $this->assertEquals(2, $gi->getInitialStandardDeviation());
        $this->assertEquals(3, $gi->getBeta());
        $this->assertEquals(4, $gi->getDynamicsFactor());
        $this->assertEquals(5, $gi->getDrawProbability());
        $this->assertInstanceOf(Rating::class, $gi->getDefaultRating());
    }
}
