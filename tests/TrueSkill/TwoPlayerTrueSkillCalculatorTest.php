<?php

namespace DNW\Skills\Tests\TrueSkill;

use DNW\Skills\TrueSkill\TwoPlayerTrueSkillCalculator;
use PHPUnit\Framework\TestCase;

class TwoPlayerTrueSkillCalculatorTest extends TestCase
{
    public function testTwoPlayerTrueSkillCalculator(): void
    {
        $calculator = new TwoPlayerTrueSkillCalculator();

        // We only support two players
        TrueSkillCalculatorTests::testAllTwoPlayerScenarios($this, $calculator);
    }
}
