<?php

declare(strict_types=1);

namespace DNW\Skills\Tests\TrueSkill;

use DNW\Skills\TrueSkill\TwoTeamTrueSkillCalculator;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversNothing;

final class TwoTeamTrueSkillCalculatorTest extends TestCase
{
    #[CoversNothing]
    public function testTwoTeamTrueSkillCalculator(): void
    {
        $calculator = new TwoTeamTrueSkillCalculator();

        TrueSkillCalculatorTests::testAllTwoPlayerScenarios($this, $calculator);
        TrueSkillCalculatorTests::testAllTwoTeamScenarios($this, $calculator);
    }
}
