<?php

namespace DNW\Skills\TrueSkill\Layers;

use DNW\Skills\FactorGraphs\ScheduleStep;
use DNW\Skills\FactorGraphs\ScheduleSequence;
use DNW\Skills\PartialPlay;
use DNW\Skills\Player;
use DNW\Skills\Team;
use DNW\Skills\TrueSkill\Factors\GaussianWeightedSumFactor;
use DNW\Skills\FactorGraphs\Variable; 

class PlayerPerformancesToTeamPerformancesLayer extends TrueSkillFactorGraphLayer
{
    public function buildLayer(): void
    {
        $inputVariablesGroups = $this->getInputVariablesGroups();
        foreach ($inputVariablesGroups as $currentTeam) {
            $localCurrentTeam = $currentTeam;
            $teamPerformance = $this->createOutputVariable($localCurrentTeam);
            $newSumFactor = $this->createPlayerToTeamSumFactor($localCurrentTeam, $teamPerformance);

            $this->addLayerFactor($newSumFactor);

            // REVIEW: Does it make sense to have groups of one?
            $outputVariablesGroups = &$this->getOutputVariablesGroups();
            $outputVariablesGroups[] = [$teamPerformance];
        }
    }

    public function createPriorSchedule(): ?ScheduleSequence
    {
        $localFactors = $this->getLocalFactors();

        return $this->scheduleSequence(
            array_map(
                fn ($weightedSumFactor) => new ScheduleStep('Perf to Team Perf Step', $weightedSumFactor, 0),
                $localFactors
            ),
            'all player perf to team perf schedule'
        );
    }

    /**
     * @param Team[] $teamMembers
     */
    protected function createPlayerToTeamSumFactor(array $teamMembers, Variable $sumVariable): GaussianWeightedSumFactor
    {
        $weights = array_map(
            function ($v) {
                $player = $v->getKey();

                return PartialPlay::getPartialPlayPercentage($player);
            },
            $teamMembers
        );

        return new GaussianWeightedSumFactor(
            $sumVariable,
            $teamMembers,
            $weights
        );
    }

    public function createPosteriorSchedule(): ?ScheduleSequence
    {
        $allFactors = [];
        $localFactors = $this->getLocalFactors();
        foreach ($localFactors as $currentFactor) {
            $localCurrentFactor = $currentFactor;
            $numberOfMessages = $localCurrentFactor->getNumberOfMessages();
            for ($currentIteration = 1; $currentIteration < $numberOfMessages; $currentIteration++) {
                $allFactors[] = new ScheduleStep(
                    'team sum perf @' . $currentIteration,
                    $localCurrentFactor,
                    $currentIteration
                );
            }
        }

        return $this->scheduleSequence($allFactors, "all of the team's sum iterations");
    }

    private function createOutputVariable(array $team): Variable
    {
        $memberNames = array_map(fn ($currentPlayer) => (string) ($currentPlayer->getKey()), $team);

        $teamMemberNames = \implode(', ', $memberNames);

        return $this->getParentFactorGraph()->getVariableFactory()->createBasicVariable('Team[' . $teamMemberNames . "]'s performance");
    }
}
