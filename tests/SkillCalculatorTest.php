<?php

declare(strict_types=1);

namespace DNW\Skills\Tests;

use DNW\Skills\SkillCalculator;
use DNW\Skills\TeamsRange;
use DNW\Skills\PlayersRange;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\RequiresPhpunit;

#[CoversClass(SkillCalculator::class)]
#[UsesClass(\DNW\Skills\Numerics\Range::class)]
#[UsesClass(PlayersRange::class)]
#[UsesClass(TeamsRange::class)]
#[RequiresPhpunit('<12.0')]
final class SkillCalculatorTest extends TestCase
{
    public function testisSupported(): void
    {
        $calculator = $this->getMockForAbstractClass(SkillCalculator::class, [SkillCalculator::PARTIAL_PLAY, new TeamsRange(1, 2), new PlayersRange(1, 2)]);

        $this->assertEquals(TRUE, $calculator->isSupported(SkillCalculator::PARTIAL_PLAY));
        $this->assertEquals(FALSE, $calculator->isSupported(SkillCalculator::PARTIAL_UPDATE));
    }
}
