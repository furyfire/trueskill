<?php

declare(strict_types=1);

namespace DNW\Skills\Benchmark;

use DNW\Skills\TrueSkill\TwoPlayerTrueSkillCalculator;
use DNW\Skills\TrueSkill\TwoTeamTrueSkillCalculator;
use DNW\Skills\TrueSkill\FactorGraphTrueSkillCalculator;
use DNW\Skills\GameInfo;
use DNW\Skills\Player;
use DNW\Skills\Team;
use DNW\Skills\Teams;

class BasicBench
{
    /**
     * To benchmark performance when using TwoPlayerTrueSkillCalculator
     *
     * @Revs(20)
     * @Iterations(20)
     */
    public function benchBasic2PlayersUsingTwoPlayerTrueSkillCalculator(): void
    {
        $gameInfo = new GameInfo();

        $p1 = new Player("Winner");
        $p2 = new Player("Average");

        $team1 = new Team($p1, $gameInfo->getDefaultRating());
        $team2 = new Team($p2, $gameInfo->getDefaultRating());

        for ($i = 0; $i < 10; ++$i) {
            $teams = [$team1, $team2];

            $calculator = new TwoPlayerTrueSkillCalculator();

            $newRatings = $calculator->calculateNewRatings($gameInfo, $teams, [1, 2]);

            $team1 = new Team($p1, $newRatings->getRating($p1));
            $team2 = new Team($p2, $newRatings->getRating($p2));

            $newRatings->getRating($p1)->getConservativeRating();
            $newRatings->getRating($p2)->getConservativeRating();
        }
    }

    /**
     * To benchmark performance when using TwoTeamTrueSkillCalculator for just two players in two teams
     *
     * @Revs(20)
     * @Iterations(20)
     */
    public function benchBasic2PlayersUsingTwoTeamTrueSkillCalculator(): void
    {
        $gameInfo = new GameInfo();

        $p1 = new Player("Winner");
        $p2 = new Player("Average");

        $team1 = new Team($p1, $gameInfo->getDefaultRating());
        $team2 = new Team($p2, $gameInfo->getDefaultRating());

        for ($i = 0; $i < 10; ++$i) {
            $teams = [$team1, $team2];

            $calculator = new TwoTeamTrueSkillCalculator();

            $newRatings = $calculator->calculateNewRatings($gameInfo, $teams, [1, 2]);

            $team1 = new Team($p1, $newRatings->getRating($p1));
            $team2 = new Team($p2, $newRatings->getRating($p2));

            $newRatings->getRating($p1)->getConservativeRating();
            $newRatings->getRating($p2)->getConservativeRating();
        }
    }

    /**
     * To benchmark performance when using FactorGraphTrueSkillCalculator for just two players in two teams
     *
     * @Revs(20)
     * @Iterations(20)
     */
    public function benchBasic2PlayersUsingFactorGraphTrueSkillCalculator(): void
    {
        $gameInfo = new GameInfo();

        $p1 = new Player("Winner");
        $p2 = new Player("Average");

        $team1 = new Team($p1, $gameInfo->getDefaultRating());
        $team2 = new Team($p2, $gameInfo->getDefaultRating());

        for ($i = 0; $i < 10; ++$i) {
            $teams = [$team1, $team2];

            $calculator = new FactorGraphTrueSkillCalculator();

            $newRatings = $calculator->calculateNewRatings($gameInfo, $teams, [1, 2]);

            $team1 = new Team($p1, $newRatings->getRating($p1));
            $team2 = new Team($p2, $newRatings->getRating($p2));

            $newRatings->getRating($p1)->getConservativeRating();
            $newRatings->getRating($p2)->getConservativeRating();
        }
    }

    /**
     * To benchmark performance when using FactorGraphTrueSkillCalculator with 3 players in 3 teams
     *
     * @Revs(20)
     * @Iterations(20)
     */
    public function bench3Teams(): void
    {
        $gameInfo = new GameInfo();

        $p1 = new Player("Winner");
        $p2 = new Player("Average");
        $p3 = new Player("Looser");

        $team1 = new Team($p1, $gameInfo->getDefaultRating());
        $team2 = new Team($p2, $gameInfo->getDefaultRating());
        $team3 = new Team($p3, $gameInfo->getDefaultRating());
        
        for ($i = 0; $i < 10; ++$i) {
            $teams = [$team1, $team2, $team3];

            $calculator = new FactorGraphTrueSkillCalculator();

            $newRatings = $calculator->calculateNewRatings($gameInfo, $teams, [1, 2, 3]);

            $team1 = new Team($p1, $newRatings->getRating($p1));
            $team2 = new Team($p2, $newRatings->getRating($p2));
            $team3 = new Team($p3, $newRatings->getRating($p3));

            $newRatings->getRating($p1)->getConservativeRating();
            $newRatings->getRating($p2)->getConservativeRating();
            $newRatings->getRating($p3)->getConservativeRating();
        }
    }
}
