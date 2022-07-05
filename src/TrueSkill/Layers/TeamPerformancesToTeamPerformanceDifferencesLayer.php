<?php

namespace DNW\Skills\TrueSkill\Layers;

use DNW\Skills\FactorGraphs\Variable;
use DNW\Skills\TrueSkill\Factors\GaussianWeightedSumFactor;
use DNW\Skills\TrueSkill\TrueSkillFactorGraph;

class TeamPerformancesToTeamPerformanceDifferencesLayer extends TrueSkillFactorGraphLayer
{
    public function __construct(TrueSkillFactorGraph $parentGraph)
    {
        parent::__construct($parentGraph);
    }

    public function buildLayer()
    {
        $inputVariablesGroups = $this->getInputVariablesGroups();
        $inputVariablesGroupsCount = count($inputVariablesGroups);
        $outputVariablesGroup = &$this->getOutputVariablesGroups();

        for ($i = 0; $i < $inputVariablesGroupsCount - 1; $i++) {
            $strongerTeam = $inputVariablesGroups[$i][0];
            $weakerTeam = $inputVariablesGroups[$i + 1][0];

            $currentDifference = $this->createOutputVariable();
            $newDifferencesFactor = $this->createTeamPerformanceToDifferenceFactor($strongerTeam, $weakerTeam, $currentDifference);
            $this->addLayerFactor($newDifferencesFactor);

            // REVIEW: Does it make sense to have groups of one?
            $outputVariablesGroup[] = [$currentDifference];
        }
    }

    private function createTeamPerformanceToDifferenceFactor(Variable $strongerTeam,
                                                             Variable $weakerTeam,
                                                             Variable $output)
    {
        $teams = [$strongerTeam, $weakerTeam];
        $weights = [1.0, -1.0];

        return new GaussianWeightedSumFactor($output, $teams, $weights);
    }

    private function createOutputVariable()
    {
        $outputVariable = $this->getParentFactorGraph()->getVariableFactory()->createBasicVariable('Team performance difference');

        return $outputVariable;
    }
}
