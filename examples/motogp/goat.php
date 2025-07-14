<?php

require __DIR__ . "/../../vendor/autoload.php";

use League\Csv\Reader;
use League\Csv\Statement;
use DNW\Skills\TrueSkill\FactorGraphTrueSkillCalculator;
use DNW\Skills\GameInfo;
use DNW\Skills\Player;
use DNW\Skills\Team;
use DNW\Skills\Teams;

//load the CSV document from a stream
$csv = Reader::createFromPath('motogp.csv', 'r');
$csv->setDelimiter(',');
$csv->setHeaderOffset(0);
$csv->setEscape('');

//build a statement
$statement = new Statement();
$stmt = $statement->where(static fn (array $record): bool => $record['category'] == "MotoGP" ||  $record['category'] == "500cc");

/**
 * @var $riders Player[]
 */
$riders = [];
//query your records from the document
$records = $stmt->process($csv);

$gameInfo = new GameInfo();
$calculator = new FactorGraphTrueSkillCalculator();
$first_record = $records->first();
$year_race = $first_record['year'] . '_' . $first_record['sequence'] . '_' . $first_record['category'];

$race_rate = [];
foreach ($records as $record) {
    if ($year_race !== $record['year'] . '_' . $record['sequence'] . '_' . $record['category']) {
        //Calculate the old race
        $newRatings = $calculator->calculateNewRatings($gameInfo, $teams, $pos);

        //update ratings
        $highest_rate = 0;
        $highest_rider = "";
        foreach ($riders as $rider) {
            //echo $rider['P']->getId().": ". $newRatings->getRating($rider['P'])->getConservativeRating() . PHP_EOL;
            $rider['T']->setRating($rider['P'], $newRatings->getRating($rider['P']));
            if ($newRatings->getRating($rider['P'])->getConservativeRating() > $highest_rate) {
                $highest_rate = $newRatings->getRating($rider['P'])->getConservativeRating();
                $highest_rider = $rider['P']->getId();
            }
        }

        echo sprintf('Highest rider: %s => %s', $highest_rider, $highest_rate) . PHP_EOL;

        foreach ($global_riders as $r) {
            $rate = $r['T']->getRating($r['P'])->getConservativeRating();

            $race_rate[$year_race][$r['P']->getId()] = $rate;
            if (! isset($top_rating[$r['P']->getId()]) || $top_rating[$r['P']->getId()] < $rate) {
                $top_rating[$r['P']->getId()] = $rate;
            }
        }

        //prepare for next race
        $year_race = $record['year'] . '_' . $record['sequence'] . '_' . $record['category'];
        $races[] = ['year' => $record['year'], 'race' => $record['sequence'], 'circuit' => $record['circuit_name']];
        echo "New Race: " . $year_race . ' => ' . $record['circuit_name'] . PHP_EOL;
        $riders = [];
        $teams = [];
        $pos = [];
    }

    //Is it a new rider?
    if (! isset($global_riders[$record['rider']])) {
        $global_riders[$record['rider']]['P'] = new Player($record['rider_name']);
        $global_riders[$record['rider']]['T'] = new Team($global_riders[$record['rider']]['P'], $gameInfo->getDefaultRating());
        //echo "New Rider: ". $record['rider'] . " => ".$global_riders[$record['rider']]['P']->getId().PHP_EOL;
    }

    $riders[] = $global_riders[$record['rider']];
    $teams[] = $global_riders[$record['rider']]['T'];
    
    //Position or DNF?
    $pos[] = $record['position'] >= 1 ? $record['position'] : end($pos);
}

echo "All time top score" . PHP_EOL;
asort($top_rating);
foreach ($top_rating as $n => $r) {
    echo sprintf('%s => %s', $n, $r) . PHP_EOL;
}
