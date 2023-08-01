<?php

namespace DNW\Skills\TrueSkill\Layers;

use DNW\Skills\FactorGraphs\ScheduleStep;
use DNW\Skills\FactorGraphs\Variable;
use DNW\Skills\Numerics\BasicMath;
use DNW\Skills\Rating;
use DNW\Skills\TrueSkill\Factors\GaussianPriorFactor;
use DNW\Skills\TrueSkill\TrueSkillFactorGraph;

// We intentionally have no Posterior schedule since the only purpose here is to
// start the process.
class PlayerPriorValuesToSkillsLayer extends TrueSkillFactorGraphLayer
{
    public function __construct(TrueSkillFactorGraph $parentGraph, private readonly array $_teams)
    {
        parent::__construct($parentGraph);
    }

    public function buildLayer()
    {
        $teams = $this->_teams;
        foreach ($teams as $currentTeam) {
            $localCurrentTeam = $currentTeam;
            $currentTeamSkills = [];

            $currentTeamAllPlayers = $localCurrentTeam->getAllPlayers();
            foreach ($currentTeamAllPlayers as $currentTeamPlayer) {
                $localCurrentTeamPlayer = $currentTeamPlayer;
                $currentTeamPlayerRating = $currentTeam->getRating($localCurrentTeamPlayer);
                $playerSkill = $this->createSkillOutputVariable($localCurrentTeamPlayer);
                $priorFactor = $this->createPriorFactor($currentTeamPlayerRating, $playerSkill);
                $this->addLayerFactor($priorFactor);
                $currentTeamSkills[] = $playerSkill;
            }

            $outputVariablesGroups = &$this->getOutputVariablesGroups();
            $outputVariablesGroups[] = $currentTeamSkills;
        }
    }

    public function createPriorSchedule()
    {
        $localFactors = $this->getLocalFactors();

        return $this->scheduleSequence(
            array_map(
                fn ($prior) => new ScheduleStep('Prior to Skill Step', $prior, 0),
                $localFactors
            ),
            'All priors'
        );
    }

    private function createPriorFactor(Rating $priorRating, Variable $skillsVariable)
    {
        return new GaussianPriorFactor(
            $priorRating->getMean(),
            BasicMath::square($priorRating->getStandardDeviation()) +
            BasicMath::square($this->getParentFactorGraph()->getGameInfo()->getDynamicsFactor()),
            $skillsVariable
        );
    }

    private function createSkillOutputVariable($key)
    {
        $parentFactorGraph = $this->getParentFactorGraph();
        $variableFactory = $parentFactorGraph->getVariableFactory();

        return $variableFactory->createKeyedVariable($key, $key . "'s skill");
    }
}
