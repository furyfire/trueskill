<?php

namespace DNW\Skills\Tests\TrueSkill;

use DNW\Skills\GameInfo;
use DNW\Skills\Player;
use DNW\Skills\Team;
use DNW\Skills\TrueSkill\FactorGraphTrueSkillCalculator;
use PHPUnit\Framework\TestCase;

class FactorGraphTrueSkillCalculatorTest extends TestCase
{
    public function testMicrosoftResearchExample(): void
    {
        $gameInfo = new GameInfo();

        $players = [
            new Player("alice"),
            new Player("bob"),
            new Player("chris"),
            new Player("darren"),
            new Player("eve"),
            new Player("fabien"),
            new Player("george"),
            new Player("hillary"),
        ];

        $teams = array();
        foreach ($players as $player) {
            $teams[] = new Team($player, $gameInfo->getDefaultRating());
        }

        $calculator = new FactorGraphTrueSkillCalculator();

        $newRatings = $calculator->calculateNewRatings($gameInfo, $teams, [1,2,3,4,5,6,7,8]);

        $expected = [
            'alice'   => [36.771, 5.749],
            'bob'     => [32.242, 5.133],
            'chris'   => [29.074, 4.943],
            'darren'  => [26.322, 4.874],
            'eve'     => [23.678, 4.874],
            'fabien'  => [20.926, 4.943],
            'george'  => [17.758, 5.133],
            'hillary' => [13.229, 5.749],
        ];

        foreach ($players as $player) {
            $rating = $newRatings->getRating($player);
            $this->assertEqualsWithDelta($expected[$player->getId()][0], $rating->getMean(), 0.001);
            $this->assertEqualsWithDelta($expected[$player->getId()][1], $rating->getStandardDeviation(), 0.001);
        }
    }
}
