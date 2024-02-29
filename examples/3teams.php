<?php

require_once(__DIR__ . "/../vendor/autoload.php");

use DNW\Skills\TrueSkill\FactorGraphTrueSkillCalculator;
use DNW\Skills\GameInfo;
use DNW\Skills\Player;
use DNW\Skills\Team;
use DNW\Skills\Teams;

$gameInfo = new GameInfo();

$p1 = new Player("Winner");
$p2 = new Player("Average");
$p3 = new Player("Looser");


$team1 = new Team($p1, $gameInfo->getDefaultRating());
$team2 = new Team($p2, $gameInfo->getDefaultRating());
$team3 = new Team($p3, $gameInfo->getDefaultRating());


for($i = 0; $i < 5; ++$i) {
    echo "Iteration: " . $i . PHP_EOL;
    $teams = Teams::concat($team1, $team2, $team3);

    $calculator = new FactorGraphTrueSkillCalculator();

    $newRatings = $calculator->calculateNewRatings($gameInfo, $teams, [1, 2, 3]);

    $team1 = new Team($p1, $newRatings->getRating($p1));
    $team2 = new Team($p2, $newRatings->getRating($p2));
    $team3 = new Team($p3, $newRatings->getRating($p3));

    echo "P1: ". $newRatings->getRating($p1)->getConservativeRating() . PHP_EOL;
    echo "P2: ". $newRatings->getRating($p2)->getConservativeRating() . PHP_EOL;
    echo "P3: ". $newRatings->getRating($p3)->getConservativeRating() . PHP_EOL;
}



