<?php

declare(strict_types=1);

namespace DNW\Skills\Tests\TrueSkill;

use DNW\Skills\TrueSkill\TwoPlayerTrueSkillCalculator;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversClass(TwoPlayerTrueSkillCalculator::class)]
class TwoPlayerTrueSkillCalculatorTest extends TestCase
{
    #[CoversNothing]
    public function testTwoPlayerTrueSkillCalculator(): void
    {
        $calculator = new TwoPlayerTrueSkillCalculator();

        // We only support two players
        TrueSkillCalculatorTests::testAllTwoPlayerScenarios($this, $calculator);
    }
}
