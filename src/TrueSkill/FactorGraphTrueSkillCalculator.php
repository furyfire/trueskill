<?php

declare(strict_types=1);

namespace DNW\Skills\TrueSkill;

use DNW\Skills\GameInfo;
use DNW\Skills\Guard;
use DNW\Skills\Numerics\BasicMath;
use DNW\Skills\Numerics\DiagonalMatrix;
use DNW\Skills\Numerics\Matrix;
use DNW\Skills\Numerics\Vector;
use DNW\Skills\PartialPlay;
use DNW\Skills\PlayersRange;
use DNW\Skills\RankSorter;
use DNW\Skills\SkillCalculator;
use DNW\Skills\Team;
use DNW\Skills\TeamsRange;
use DNW\Skills\RatingContainer;
use DNW\Skills\Rating;

/**
 * Calculates TrueSkill using a full factor graph.
 */
final class FactorGraphTrueSkillCalculator extends SkillCalculator
{
    public function __construct()
    {
        parent::__construct(SkillCalculator::PARTIAL_PLAY | SkillCalculator::PARTIAL_UPDATE, TeamsRange::atLeast(2), PlayersRange::atLeast(1));
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function calculateNewRatings(
        GameInfo $gameInfo,
        array $teams,
        array $teamRanks
    ): RatingContainer
    {
        $this->validateTeamCountAndPlayersCountPerTeam($teams);

        RankSorter::sort($teams, $teamRanks);

        $factorGraph = new TrueSkillFactorGraph($gameInfo, $teams, $teamRanks);
        $factorGraph->buildGraph();
        $factorGraph->runSchedule();

        $factorGraph->getProbabilityOfRanking();

        return $factorGraph->getUpdatedRatings();
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function calculateMatchQuality(GameInfo $gameInfo, array $teams): float
    {
        // We need to create the A matrix which is the player team assigments.
        $teamAssignmentsList = $teams;
        $skillsMatrix = self::getPlayerCovarianceMatrix($teamAssignmentsList);
        $meanVector = self::getPlayerMeansVector($teamAssignmentsList);
        $meanVectorTranspose = $meanVector->getTranspose();

        $playerTeamAssignmentsMatrix = self::createPlayerTeamAssignmentMatrix($teamAssignmentsList, $meanVector->getRowCount());
        $playerTeamAssignmentsMatrixTranspose = $playerTeamAssignmentsMatrix->getTranspose();

        $betaSquared = BasicMath::square($gameInfo->getBeta());

        $start = Matrix::multiply($meanVectorTranspose, $playerTeamAssignmentsMatrix);

        $aTa = Matrix::multiply(
            Matrix::scalarMultiply($betaSquared, $playerTeamAssignmentsMatrixTranspose),
            $playerTeamAssignmentsMatrix
        );

        $aTSA = Matrix::multiply(
            Matrix::multiply($playerTeamAssignmentsMatrixTranspose, $skillsMatrix),
            $playerTeamAssignmentsMatrix
        );

        $middle = Matrix::add($aTa, $aTSA);

        $middleInverse = $middle->getInverse();

        $end = Matrix::multiply($playerTeamAssignmentsMatrixTranspose, $meanVector);

        $expPartMatrix = Matrix::scalarMultiply(-0.5, (Matrix::multiply(Matrix::multiply($start, $middleInverse), $end)));
        $expPart = $expPartMatrix->getDeterminant();

        $sqrtPartNumerator = $aTa->getDeterminant();
        $sqrtPartDenominator = $middle->getDeterminant();
        $sqrtPart = $sqrtPartNumerator / $sqrtPartDenominator;

        return exp($expPart) * sqrt($sqrtPart);
    }

    /**
     * @param Team[] $teamAssignmentsList
     */
    private static function getPlayerMeansVector(array $teamAssignmentsList): Vector
    {
        // A simple vector of all the player means.
        return new Vector(
            self::getPlayerRatingValues(
                $teamAssignmentsList,
                static fn(Rating $rating): float => $rating->getMean()
            )
        );
    }

    /**
     * @param Team[] $teamAssignmentsList
     */
    private static function getPlayerCovarianceMatrix(array $teamAssignmentsList): DiagonalMatrix
    {
        // This is a square matrix whose diagonal values represent the variance (square of standard deviation) of all
        // players.
        return new DiagonalMatrix(
            self::getPlayerRatingValues(
                $teamAssignmentsList,
                static fn(Rating $rating): float => BasicMath::square($rating->getStandardDeviation())
            )
        );
    }


    /**
     * Helper function that gets a list of values for all player ratings
     *
     * @param Team[] $teamAssignmentsList
     *
     * @return int[]
     */
    private static function getPlayerRatingValues(array $teamAssignmentsList, \Closure $playerRatingFunction): array
    {
        $playerRatingValues = [];

        foreach ($teamAssignmentsList as $currentTeam) {
            foreach ($currentTeam->getAllRatings() as $currentRating) {
                $playerRatingValues[] = $playerRatingFunction($currentRating);
            }
        }

        return $playerRatingValues;
    }

    /**
     * @param Team[] $teamAssignmentsList
     */
    private static function createPlayerTeamAssignmentMatrix(array $teamAssignmentsList, int $totalPlayers): Matrix
    {
        // The team assignment matrix is often referred to as the "A" matrix. It's a matrix whose rows represent the players
        // and the columns represent teams. At Matrix[row, column] represents that player[row] is on team[col]
        // Positive values represent an assignment and a negative value means that we subtract the value of the next
        // team since we're dealing with pairs. This means that this matrix always has teams - 1 columns.
        // The only other tricky thing is that values represent the play percentage.

        // For example, consider a 3 team game where team1 is just player1, team 2 is player 2 and player 3, and
        // team3 is just player 4. Furthermore, player 2 and player 3 on team 2 played 25% and 75% of the time
        // (e.g. partial play), the A matrix would be:

        // A = this 4x2 matrix:
        // |  1.00  0.00 |
        // | -0.25  0.25 |
        // | -0.75  0.75 |
        // |  0.00 -1.00 |

        $playerAssignments = [];
        $totalPreviousPlayers = 0;

        $currentColumn = 0;

        $teamCnt = count($teamAssignmentsList);
        for ($i = 0; $i < $teamCnt - 1; ++$i) {
            $currentTeam = $teamAssignmentsList[$i];

            // Need to add in 0's for all the previous players, since they're not
            // on this team
            $playerAssignments[$currentColumn] = ($totalPreviousPlayers > 0) ? \array_fill(0, $totalPreviousPlayers, 0) : [];

            foreach ($currentTeam->getAllPlayers() as $currentPlayer) {
                $playerAssignments[$currentColumn][] = PartialPlay::getPartialPlayPercentage($currentPlayer);
                // indicates the player is on the team
                ++$totalPreviousPlayers;
            }

            $rowsRemaining = $totalPlayers - $totalPreviousPlayers;

            $nextTeam = $teamAssignmentsList[$i + 1];
            foreach ($nextTeam->getAllPlayers() as $nextTeamPlayer) {
                // Add a -1 * playing time to represent the difference
                $playerAssignments[$currentColumn][] = -1.0 * PartialPlay::getPartialPlayPercentage($nextTeamPlayer);
                --$rowsRemaining;
            }

            for ($ixAdditionalRow = 0; $ixAdditionalRow < $rowsRemaining; ++$ixAdditionalRow) {
                // Pad with zeros
                $playerAssignments[$currentColumn][] = 0;
            }

            ++$currentColumn;
        }

        return Matrix::fromColumnValues($totalPlayers, count($teamAssignmentsList) - 1, $playerAssignments);
    }
}
