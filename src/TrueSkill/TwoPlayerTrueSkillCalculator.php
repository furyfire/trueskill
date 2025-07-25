<?php

declare(strict_types=1);

namespace DNW\Skills\TrueSkill;

use DNW\Skills\GameInfo;
use DNW\Skills\Guard;
use DNW\Skills\Numerics\BasicMath;
use DNW\Skills\PairwiseComparison;
use DNW\Skills\PlayersRange;
use DNW\Skills\RankSorter;
use DNW\Skills\Rating;
use DNW\Skills\RatingContainer;
use DNW\Skills\SkillCalculator;
use DNW\Skills\TeamsRange;

/**
 * Calculates the new ratings for only two players.
 *
 * When you only have two players, a lot of the math simplifies. The main purpose of this class
 * is to show the bare minimum of what a TrueSkill implementation should have.
 */
final class TwoPlayerTrueSkillCalculator extends SkillCalculator
{
    public function __construct()
    {
        parent::__construct(SkillCalculator::NONE, TeamsRange::exactly(2), PlayersRange::exactly(1));
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function calculateNewRatings(GameInfo $gameInfo, array $teams, array $teamRanks): RatingContainer
    {
        // Basic argument checking
        $this->validateTeamCountAndPlayersCountPerTeam($teams);

        // Make sure things are in order
        RankSorter::sort($teams, $teamRanks);

        // Since we verified that each team has one player, we know the player is the first one
        $winningTeamPlayers = $teams[0]->getAllPlayers();
        $winner = $winningTeamPlayers[0];
        $winnerPreviousRating = $teams[0]->getRating($winner);

        $losingTeamPlayers = $teams[1]->getAllPlayers();
        $loser = $losingTeamPlayers[0];
        $loserPreviousRating = $teams[1]->getRating($loser);

        $wasDraw = ($teamRanks[0] == $teamRanks[1]);

        $results = new RatingContainer();

        $results->setRating(
            $winner,
            self::calculateNewRating(
                $gameInfo,
                $winnerPreviousRating,
                $loserPreviousRating,
                $wasDraw ? PairwiseComparison::DRAW
                : PairwiseComparison::WIN
            )
        );

        $results->setRating(
            $loser,
            self::calculateNewRating(
                $gameInfo,
                $loserPreviousRating,
                $winnerPreviousRating,
                $wasDraw ? PairwiseComparison::DRAW
                : PairwiseComparison::LOSE
            )
        );

        // And we're done!
        return $results;
    }

    private static function calculateNewRating(GameInfo $gameInfo, Rating $selfRating, Rating $opponentRating, PairwiseComparison $comparison): Rating
    {
        $drawMargin = DrawMargin::getDrawMarginFromDrawProbability(
            $gameInfo->getDrawProbability(),
            $gameInfo->getBeta()
        );

        $c = sqrt(
            BasicMath::square($selfRating->getStandardDeviation())
            +
            BasicMath::square($opponentRating->getStandardDeviation())
            +
            2.0 * BasicMath::square($gameInfo->getBeta())
        );

        $winningMean = $selfRating->getMean();
        $losingMean = $opponentRating->getMean();

        switch ($comparison) {
            case PairwiseComparison::WIN:
            case PairwiseComparison::DRAW:
                // NOP
                break;
            case PairwiseComparison::LOSE:
                $winningMean = $opponentRating->getMean();
                $losingMean = $selfRating->getMean();
                break;
        }

        $meanDelta = $winningMean - $losingMean;

        if ($comparison != PairwiseComparison::DRAW) {
            // non-draw case
            $v = TruncatedGaussianCorrectionFunctions::vExceedsMarginScaled($meanDelta, $drawMargin, $c);
            $w = TruncatedGaussianCorrectionFunctions::wExceedsMarginScaled($meanDelta, $drawMargin, $c);
            $rankMultiplier = (float)$comparison->value;
        } else {
            $v = TruncatedGaussianCorrectionFunctions::vWithinMarginScaled($meanDelta, $drawMargin, $c);
            $w = TruncatedGaussianCorrectionFunctions::wWithinMarginScaled($meanDelta, $drawMargin, $c);
            $rankMultiplier = 1.0;
        }

        $meanMultiplier = (BasicMath::square($selfRating->getStandardDeviation()) + BasicMath::square($gameInfo->getDynamicsFactor())) / $c;

        $varianceWithDynamics = BasicMath::square($selfRating->getStandardDeviation()) + BasicMath::square($gameInfo->getDynamicsFactor());
        $stdDevMultiplier = $varianceWithDynamics / BasicMath::square($c);

        $newMean = $selfRating->getMean() + ($rankMultiplier * $meanMultiplier * $v);
        $newStdDev = sqrt($varianceWithDynamics * (1.0 - $w * $stdDevMultiplier));

        return new Rating($newMean, $newStdDev);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function calculateMatchQuality(GameInfo $gameInfo, array $teams): float
    {
        $this->validateTeamCountAndPlayersCountPerTeam($teams);

        $team1 = $teams[0];
        $team2 = $teams[1];

        $team1Ratings = $team1->getAllRatings();
        $team2Ratings = $team2->getAllRatings();

        $player1Rating = $team1Ratings[0];
        $player2Rating = $team2Ratings[0];

        // We just use equation 4.1 found on page 8 of the TrueSkill 2006 paper:
        $betaSquared = BasicMath::square($gameInfo->getBeta());
        $player1SigmaSquared = BasicMath::square($player1Rating->getStandardDeviation());
        $player2SigmaSquared = BasicMath::square($player2Rating->getStandardDeviation());

        // This is the square root part of the equation:
        $sqrtPart = sqrt(
            (2.0 * $betaSquared)
            /
            (2.0 * $betaSquared + $player1SigmaSquared + $player2SigmaSquared)
        );

        // This is the exponent part of the equation:
        $expPart = exp(
            (-1.0 * BasicMath::square($player1Rating->getMean() - $player2Rating->getMean()))
            /
            (2.0 * (2.0 * $betaSquared + $player1SigmaSquared + $player2SigmaSquared))
        );

        return $sqrtPart * $expPart;
    }
}
