<?php

namespace DNW\Skills\Tests\TrueSkill;

use DNW\Skills\Tests\TestCase;
use DNW\Skills\GameInfo;
use DNW\Skills\Player;
use DNW\Skills\Team;
use DNW\Skills\TrueSkill\FactorGraphTrueSkillCalculator;

class FactorGraphTrueSkillCalculatorTest extends TestCase
{
    public function testMicrosoftResearchExample(): void
    {
        $gameInfo = new GameInfo();

        $players[] = new Player("alice");
        $players[] = new Player("bob");
        $players[] = new Player("chris");
        $players[] = new Player("darren");
        $players[] = new Player("eve");
        $players[] = new Player("fabien");
        $players[] = new Player("george");
        $players[] = new Player("hillary");

        foreach ($players as $player) {
            $teams[] = new Team($player, $gameInfo->getDefaultRating());
        }

        $calculator = new FactorGraphTrueSkillCalculator();

        $newRatings = $calculator->calculateNewRatings($gameInfo, $teams, [1,2,3,4,5,6,7,8]);

        $expected['alice'] = [36.771, 5.749];
        $expected['bob'] = [32.242, 5.133];
        $expected['chris'] = [29.074, 4.943];
        $expected['darren'] = [26.322, 4.874];
        $expected['eve'] = [23.678, 4.874];
        $expected['fabien'] = [20.926, 4.943];
        $expected['george'] = [17.758, 5.133];
        $expected['hillary'] = [13.229, 5.749];

        foreach ($players as $player) {
            $rating = $newRatings->getRating($player);
            $this->assertEqualsWithDelta($expected[$player->getId()][0], $rating->getMean(), 0.001);
            $this->assertEqualsWithDelta($expected[$player->getId()][1], $rating->getStandardDeviation(), 0.001);
        }
    }
}
