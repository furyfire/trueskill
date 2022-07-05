<?php namespace DNW\Skills\Tests\TrueSkill;

use DNW\Skills\Tests\TestCase;
use DNW\Skills\TrueSkill\TwoTeamTrueSkillCalculator;

class TwoTeamTrueSkillCalculatorTest extends TestCase
{
    public function testTwoTeamTrueSkillCalculator()
    {
        $calculator = new TwoTeamTrueSkillCalculator();

        // We only support two players
        TrueSkillCalculatorTests::testAllTwoPlayerScenarios($this, $calculator);
        TrueSkillCalculatorTests::testAllTwoTeamScenarios($this, $calculator);
    }
}