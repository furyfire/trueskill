<?php namespace DNW\Skills\Tests\TrueSkill;

use DNW\Skills\Tests\TestCase;
use DNW\Skills\TrueSkill\FactorGraphTrueSkillCalculator;

class FactorGraphTrueSkillCalculatorTest extends TestCase
{
    public function testFactorGraphTrueSkillCalculator()
    {
        $calculator = new FactorGraphTrueSkillCalculator();

        TrueSkillCalculatorTests::testAllTwoPlayerScenarios($this, $calculator);
        TrueSkillCalculatorTests::testAllTwoTeamScenarios($this, $calculator);
        TrueSkillCalculatorTests::testAllMultipleTeamScenarios($this, $calculator);
        TrueSkillCalculatorTests::testPartialPlayScenarios($this, $calculator);
    }
}