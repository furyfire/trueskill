<?php

declare(strict_types=1);

namespace DNW\Skills\TrueSkill\Layers;

use DNW\Skills\FactorGraphs\KeyedVariable;
use DNW\Skills\FactorGraphs\Variable;
use DNW\Skills\FactorGraphs\ScheduleStep;
use DNW\Skills\Numerics\BasicMath;
use DNW\Skills\TrueSkill\Factors\GaussianLikelihoodFactor;
use DNW\Skills\FactorGraphs\ScheduleSequence;

class PlayerSkillsToPerformancesLayer extends TrueSkillFactorGraphLayer
{
    public function buildLayer(): void
    {
        $inputVariablesGroups = $this->getInputVariablesGroups();
        $outputVariablesGroups = &$this->getOutputVariablesGroups();

        foreach ($inputVariablesGroups as $currentTeam) {
            $currentTeamPlayerPerformances = [];

            /**
             * @var Variable $playerSkillVariable
             */
            foreach ($currentTeam as $playerSkillVariable) {
                $localPlayerSkillVariable = $playerSkillVariable;
                $currentPlayer = ($localPlayerSkillVariable instanceof KeyedVariable) ? $localPlayerSkillVariable->getKey() : "";
                $playerPerformance = $this->createOutputVariable($currentPlayer);
                $newLikelihoodFactor = $this->createLikelihood($localPlayerSkillVariable, $playerPerformance);
                $this->addLayerFactor($newLikelihoodFactor);
                $currentTeamPlayerPerformances[] = $playerPerformance;
            }

            $outputVariablesGroups[] = $currentTeamPlayerPerformances;
        }
    }

    private function createLikelihood(Variable $playerSkill, Variable $playerPerformance): GaussianLikelihoodFactor
    {
        return new GaussianLikelihoodFactor(
            BasicMath::square($this->getParentFactorGraph()->getGameInfo()->getBeta()),
            $playerPerformance,
            $playerSkill
        );
    }

    private function createOutputVariable(mixed $key): KeyedVariable
    {
        return $this->getParentFactorGraph()->getVariableFactory()->createKeyedVariable($key, $key . "'s performance");
    }

    public function createPriorSchedule(): ?ScheduleSequence
    {
        $localFactors = $this->getLocalFactors();

        return $this->scheduleSequence(
            array_map(
                static fn($likelihood): ScheduleStep => new ScheduleStep('Skill to Perf step', $likelihood, 0),
                $localFactors
            ),
            'All skill to performance sending'
        );
    }

    public function createPosteriorSchedule(): ?ScheduleSequence
    {
        $localFactors = $this->getLocalFactors();

        return $this->scheduleSequence(
            array_map(
                static fn($likelihood): ScheduleStep => new ScheduleStep('name', $likelihood, 1),
                $localFactors
            ),
            'All skill to performance sending'
        );
    }
}
