<?php

declare(strict_types=1);

namespace DNW\Skills\TrueSkill\Layers;

use DNW\Skills\FactorGraphs\KeyedVariable;
use DNW\Skills\FactorGraphs\Variable;
use DNW\Skills\FactorGraphs\ScheduleStep;
use DNW\Skills\Numerics\BasicMath;
use DNW\Skills\TrueSkill\Factors\GaussianLikelihoodFactor;
use DNW\Skills\FactorGraphs\ScheduleSequence;

final class PlayerSkillsToPerformancesLayer extends TrueSkillFactorGraphLayer
{
    #[\Override]
    public function buildLayer(): void
    {
        $inputVarGroups = $this->getInputVariablesGroups();
        $outputVarGroups = &$this->getOutputVariablesGroups();

        foreach ($inputVarGroups as $currentTeam) {
            $currentTeamPlayerPerformances = [];

            /**
             * @var Variable $playerSkillVar
             */
            foreach ($currentTeam as $playerSkillVar) {
                $localPlayerSkillVar = $playerSkillVar;
                $currentPlayer = ($localPlayerSkillVar instanceof KeyedVariable) ? $localPlayerSkillVar->getKey() : "";
                $playerPerformance = $this->createOutputVariable($currentPlayer);
                $newLikelihoodFactor = $this->createLikelihood($localPlayerSkillVar, $playerPerformance);
                $this->addLayerFactor($newLikelihoodFactor);
                $currentTeamPlayerPerformances[] = $playerPerformance;
            }

            $outputVarGroups[] = $currentTeamPlayerPerformances;
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
        return $this->getParentFactorGraph()->getVariableFactory()->createKeyedVariable($key);
    }

    #[\Override]
    public function createPriorSchedule(): ScheduleSequence
    {
        $localFactors = $this->getLocalFactors();

        //All skill to performance sending
        return $this->scheduleSequence(
            array_map(
                //Skill to Perf step
                static fn($likelihood): ScheduleStep => new ScheduleStep($likelihood, 0),
                $localFactors
            )
        );
    }

    #[\Override]
    public function createPosteriorSchedule(): ScheduleSequence
    {
        $localFactors = $this->getLocalFactors();

        //All skill to performance sending
        return $this->scheduleSequence(
            array_map(
                static fn($likelihood): ScheduleStep => new ScheduleStep($likelihood, 1),
                $localFactors
            )
        );
    }
}
