<?php

namespace DNW\Skills\Tests\TrueSkill;

use DNW\Skills\TrueSkill\FactorGraphTrueSkillCalculator;
use DNW\Skills\SkillCalculatorSupportedOptions;
use PHPUnit\Framework\TestCase;

class FactorGraphTeamTrueSkillCalculatorTest extends TestCase
{
    public function testFactorGraphTrueSkillCalculator(): void
    {
        $calculator = new FactorGraphTrueSkillCalculator();

        TrueSkillCalculatorTests::testAllTwoPlayerScenarios($this, $calculator);
        TrueSkillCalculatorTests::testAllTwoTeamScenarios($this, $calculator);
        TrueSkillCalculatorTests::testAllMultipleTeamScenarios($this, $calculator);
        TrueSkillCalculatorTests::testPartialPlayScenarios($this, $calculator);
    }

    public function testMethodisSupported(): void
    {
        $calculator = new FactorGraphTrueSkillCalculator();
        $this->assertEquals(true, $calculator->isSupported(SkillCalculatorSupportedOptions::PARTIAL_PLAY));
    }
}
