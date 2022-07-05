<?php

namespace DNW\Skills;

use Exception;

/**
 * Base class for all skill calculator implementations.
 */
abstract class SkillCalculator
{
    protected function __construct(private $_supportedOptions, private readonly TeamsRange $_totalTeamsAllowed, private readonly PlayersRange $_playersPerTeamAllowed)
    {
    }

    /**
     * Calculates new ratings based on the prior ratings and team ranks.
     *
     * @param  GameInfo  $gameInfo Parameters for the game.
     * @param  array  $teamsOfPlayerToRatings A mapping of team players and their ratings.
     * @param  array  $teamRanks The ranks of the teams where 1 is first place. For a tie, repeat the number (e.g. 1, 2, 2).
     * @return All the players and their new ratings.
     */
    abstract public function calculateNewRatings(GameInfo $gameInfo,
                                                 array $teamsOfPlayerToRatings,
                                                 array $teamRanks);

    /**
     * Calculates the match quality as the likelihood of all teams drawing.
     *
     * @param  GameInfo  $gameInfo Parameters for the game.
     * @param  array  $teamsOfPlayerToRatings A mapping of team players and their ratings.
     * @return The quality of the match between the teams as a percentage (0% = bad, 100% = well matched).
     */
    abstract public function calculateMatchQuality(GameInfo $gameInfo, array $teamsOfPlayerToRatings);

    public function isSupported($option)
    {
        return ($this->_supportedOptions & $option) == $option;
    }

    protected function validateTeamCountAndPlayersCountPerTeam(array $teamsOfPlayerToRatings)
    {
        self::validateTeamCountAndPlayersCountPerTeamWithRanges($teamsOfPlayerToRatings, $this->_totalTeamsAllowed, $this->_playersPerTeamAllowed);
    }

    /**
     * @param  array<\DNW\Skills\Team>  $teams
     * @return void
     * @throws \Exception
     */
    private static function validateTeamCountAndPlayersCountPerTeamWithRanges(array $teams, TeamsRange $totalTeams, PlayersRange $playersPerTeam)
    {
        $countOfTeams = 0;

        foreach ($teams as $currentTeam) {
            if (! $playersPerTeam->isInRange($currentTeam->count())) {
                throw new Exception('Player count is not in range');
            }
            $countOfTeams++;
        }

        if (! $totalTeams->isInRange($countOfTeams)) {
            throw new Exception('Team range is not in range');
        }
    }
}

class SkillCalculatorSupportedOptions
{
    final const NONE = 0x00;

    final const PARTIAL_PLAY = 0x01;

    final const PARTIAL_UPDATE = 0x02;
}
