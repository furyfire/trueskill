<?php

declare(strict_types=1);

namespace DNW\Skills;

use Exception;

/**
 * Base class for all skill calculator implementations.
 */
abstract class SkillCalculator
{
    public const NONE = 0x00;

    public const PARTIAL_PLAY = 0x01;

    public const PARTIAL_UPDATE = 0x02;

    protected function __construct(
        private readonly int $supportedOptions,
        private readonly TeamsRange $totalTeamsAllowed,
        private readonly PlayersRange $playersPerTeamAllowed
    )
    {
    }

    /**
     * Calculates new ratings based on the prior ratings and team ranks.
     *
     * @param GameInfo $gameInfo  Parameters for the game.
     * @param Team[]   $teams     A mapping of team players and their ratings.
     * @param int[]    $teamRanks The ranks of the teams where 1 is first place. For a tie, repeat the number (e.g. 1, 2, 2).
     *
     * @return RatingContainer All the players and their new ratings.
     */
    abstract public function calculateNewRatings(
        GameInfo $gameInfo,
        array $teams,
        array $teamRanks
    ): RatingContainer;

    /**
     * Calculates the match quality as the likelihood of all teams drawing.
     *
     * @param GameInfo $gameInfo Parameters for the game.
     * @param Team[]   $teams    A mapping of team players and their ratings.
     *
     * @return float The quality of the match between the teams as a percentage (0% = bad, 100% = well matched).
     */
    abstract public function calculateMatchQuality(GameInfo $gameInfo, array $teams): float;

    public function isSupported(int $option): bool
    {
        return (bool)($this->supportedOptions & $option) == $option;
    }

    /**
     * @param Team[] $teamsOfPlayerToRatings
     */
    protected function validateTeamCountAndPlayersCountPerTeam(array $teamsOfPlayerToRatings): void
    {
        self::validateTeamCountAndPlayersCountPerTeamWithRanges($teamsOfPlayerToRatings, $this->totalTeamsAllowed, $this->playersPerTeamAllowed);
    }

    /**
     * @param array<\DNW\Skills\Team> $teams
     *
     * @throws \Exception
     */
    private static function validateTeamCountAndPlayersCountPerTeamWithRanges(array $teams, TeamsRange $totalTeams, PlayersRange $playersPerTeam): void
    {
        $countOfTeams = 0;

        foreach ($teams as $currentTeam) {
            if (! $playersPerTeam->isInRange($currentTeam->count())) {
                throw new Exception('Player count is not in range');
            }

            ++$countOfTeams;
        }

        if (! $totalTeams->isInRange($countOfTeams)) {
            throw new Exception('Team range is not in range');
        }
    }
}
