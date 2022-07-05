<?php

namespace DNW\Skills\TrueSkill\Layers;

use DNW\Skills\FactorGraphs\KeyedVariable;
use DNW\Skills\FactorGraphs\ScheduleStep;
use DNW\Skills\Numerics\BasicMath;
use DNW\Skills\TrueSkill\Factors\GaussianLikelihoodFactor;

class PlayerSkillsToPerformancesLayer extends TrueSkillFactorGraphLayer
{
    public function buildLayer()
    {
        $inputVariablesGroups = $this->getInputVariablesGroups();
        $outputVariablesGroups = &$this->getOutputVariablesGroups();

        foreach ($inputVariablesGroups as $currentTeam) {
            $currentTeamPlayerPerformances = [];

            foreach ($currentTeam as $playerSkillVariable) {
                $localPlayerSkillVariable = $playerSkillVariable;
                $currentPlayer = $localPlayerSkillVariable->getKey();
                $playerPerformance = $this->createOutputVariable($currentPlayer);
                $newLikelihoodFactor = $this->createLikelihood($localPlayerSkillVariable, $playerPerformance);
                $this->addLayerFactor($newLikelihoodFactor);
                $currentTeamPlayerPerformances[] = $playerPerformance;
            }

            $outputVariablesGroups[] = $currentTeamPlayerPerformances;
        }
    }

    private function createLikelihood(KeyedVariable $playerSkill, KeyedVariable $playerPerformance)
    {
        return new GaussianLikelihoodFactor(
            BasicMath::square($this->getParentFactorGraph()->getGameInfo()->getBeta()),
            $playerPerformance,
            $playerSkill
        );
    }

    private function createOutputVariable($key)
    {
        return $this->getParentFactorGraph()->getVariableFactory()->createKeyedVariable($key, $key."'s performance");
    }

    public function createPriorSchedule()
    {
        $localFactors = $this->getLocalFactors();

        return $this->scheduleSequence(
            array_map(
                fn ($likelihood) => new ScheduleStep('Skill to Perf step', $likelihood, 0),
                $localFactors),
            'All skill to performance sending');
    }

    public function createPosteriorSchedule()
    {
        $localFactors = $this->getLocalFactors();

        return $this->scheduleSequence(
            array_map(
                fn ($likelihood) => new ScheduleStep('name', $likelihood, 1),
                $localFactors),
            'All skill to performance sending');
    }
}
