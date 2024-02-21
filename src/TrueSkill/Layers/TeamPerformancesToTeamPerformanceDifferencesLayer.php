<?php

declare(strict_types=1);

namespace DNW\Skills\TrueSkill\Layers;

use DNW\Skills\FactorGraphs\Variable;
use DNW\Skills\TrueSkill\Factors\GaussianWeightedSumFactor;

class TeamPerformancesToTeamPerformanceDifferencesLayer extends TrueSkillFactorGraphLayer
{
    public function buildLayer(): void
    {
        $inputVariablesGroups = $this->getInputVariablesGroups();
        $inputVariablesGroupsCount = count($inputVariablesGroups);
        $outputVariablesGroup = &$this->getOutputVariablesGroups();

        for ($i = 0; $i < $inputVariablesGroupsCount - 1; ++$i) {
            $strongerTeam = $inputVariablesGroups[$i][0];
            $weakerTeam = $inputVariablesGroups[$i + 1][0];

            $currentDifference = $this->createOutputVariable();
            $newDifferencesFactor = $this->createTeamPerformanceToDifferenceFactor($strongerTeam, $weakerTeam, $currentDifference);
            $this->addLayerFactor($newDifferencesFactor);

            // REVIEW: Does it make sense to have groups of one?
            $outputVariablesGroup[] = [$currentDifference];
        }
    }

    private function createTeamPerformanceToDifferenceFactor(
        Variable $strongerTeam,
        Variable $weakerTeam,
        Variable $output
    ): GaussianWeightedSumFactor
    {
        $teams = [$strongerTeam, $weakerTeam];
        $weights = [1.0, -1.0];

        return new GaussianWeightedSumFactor($output, $teams, $weights);
    }

    private function createOutputVariable(): Variable
    {
        return $this->getParentFactorGraph()->getVariableFactory()->createBasicVariable('Team performance difference');
    }
}
