<?php declare(strict_types=1);

namespace DNW\Skills\Tests\TrueSkill;

use DNW\Skills\TrueSkill\TwoTeamTrueSkillCalculator;
use PHPUnit\Framework\TestCase;

class TwoTeamTrueSkillCalculatorTest extends TestCase
{
    public function testTwoTeamTrueSkillCalculator(): void
    {
        $calculator = new TwoTeamTrueSkillCalculator();

        // We only support two players
        TrueSkillCalculatorTests::testAllTwoPlayerScenarios($this, $calculator);
        TrueSkillCalculatorTests::testAllTwoTeamScenarios($this, $calculator);
    }
}
