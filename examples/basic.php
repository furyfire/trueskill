<?php

require_once(__DIR__ . "/../vendor/autoload.php");

use DNW\Skills\TrueSkill\TwoPlayerTrueSkillCalculator;
use DNW\Skills\GameInfo;
use DNW\Skills\Player;
use DNW\Skills\Team;
use DNW\Skills\Teams;

$gameInfo = new GameInfo();

$p1 = new Player("Winner");
$p2 = new Player("Average");


$team1 = new Team($p1, $gameInfo->getDefaultRating());

$team2 = new Team($p2, $gameInfo->getDefaultRating());


for ($i = 0; $i < 5; ++$i) {
    echo "Iteration: " . $i . PHP_EOL;
    $teams = [$team1, $team2];

    $calculator = new TwoPlayerTrueSkillCalculator();

    $newRatings = $calculator->calculateNewRatings($gameInfo, $teams, [1, 2]);

    $team1 = new Team($p1, $newRatings->getRating($p1));
    $team2 = new Team($p2, $newRatings->getRating($p2));

    echo "P1: " . $newRatings->getRating($p1)->getConservativeRating() . PHP_EOL;
    echo "P2: " . $newRatings->getRating($p2)->getConservativeRating() . PHP_EOL;
}
