<?php

declare(strict_types=1);

namespace DNW\Skills\TrueSkill\Layers;

use DNW\Skills\FactorGraphs\ScheduleStep;
use DNW\Skills\FactorGraphs\ScheduleSequence;
use DNW\Skills\PartialPlay;
use DNW\Skills\TrueSkill\Factors\GaussianWeightedSumFactor;
use DNW\Skills\FactorGraphs\Variable;
use DNW\Skills\FactorGraphs\KeyedVariable;

class PlayerPerformancesToTeamPerformancesLayer extends TrueSkillFactorGraphLayer
{
    public function buildLayer(): void
    {
        $inputVariablesGroups = $this->getInputVariablesGroups();
        /**
         * @var KeyedVariable[] $currentTeam
         */
        foreach ($inputVariablesGroups as $currentTeam) {
            $localCurrentTeam = $currentTeam;
            $teamPerformance = $this->createOutputVariable();
            $newSumFactor = $this->createPlayerToTeamSumFactor($localCurrentTeam, $teamPerformance);

            $this->addLayerFactor($newSumFactor);

            // REVIEW: Does it make sense to have groups of one?
            $outputVarGroups = &$this->getOutputVariablesGroups();
            $outputVarGroups[] = [$teamPerformance];
        }
    }

    public function createPriorSchedule(): ?ScheduleSequence
    {
        $localFactors = $this->getLocalFactors();

        //all player perf to team perf schedule
        return $this->scheduleSequence(
            array_map(
                //Perf to Team Perf Step
                static fn($weightedSumFactor): ScheduleStep => new ScheduleStep($weightedSumFactor, 0),
                $localFactors
            )
        );
    }

    /**
     * @param KeyedVariable[] $teamMembers
     */
    protected function createPlayerToTeamSumFactor(array $teamMembers, Variable $sumVariable): GaussianWeightedSumFactor
    {
        $weights = array_map(
            static function ($v): float {
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
            for ($currentIteration = 1; $currentIteration < $numberOfMessages; ++$currentIteration) {
                //team sum perf
                $allFactors[] = new ScheduleStep(
                    $localCurrentFactor,
                    $currentIteration
                );
            }
        }

        //all of the team's sum iterations
        return $this->scheduleSequence($allFactors);
    }

    /**
     * Team's performance
     */
    private function createOutputVariable(): Variable
    {
        return $this->getParentFactorGraph()->getVariableFactory()->createBasicVariable();
    }
}
