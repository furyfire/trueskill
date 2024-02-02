<?php

declare(strict_types=1);

namespace DNW\Skills\TrueSkill\Layers;

use DNW\Skills\FactorGraphs\ScheduleStep;
use DNW\Skills\FactorGraphs\Variable;
use DNW\Skills\FactorGraphs\KeyedVariable;
use DNW\Skills\Numerics\BasicMath;
use DNW\Skills\Rating;
use DNW\Skills\Team;
use DNW\Skills\Player;
use DNW\Skills\TrueSkill\Factors\GaussianPriorFactor;
use DNW\Skills\TrueSkill\TrueSkillFactorGraph;
use DNW\Skills\FactorGraphs\ScheduleSequence;

// We intentionally have no Posterior schedule since the only purpose here is to
// start the process.
class PlayerPriorValuesToSkillsLayer extends TrueSkillFactorGraphLayer
{
    /**
     * @param Team[] $teams
     */
    public function __construct(TrueSkillFactorGraph $parentGraph, private readonly array $teams)
    {
        parent::__construct($parentGraph);
    }

    public function buildLayer(): void
    {
        $teams = $this->teams;
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

    public function createPriorSchedule(): ?ScheduleSequence
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

    private function createPriorFactor(Rating $priorRating, Variable $skillsVariable): GaussianPriorFactor
    {
        return new GaussianPriorFactor(
            $priorRating->getMean(),
            BasicMath::square($priorRating->getStandardDeviation()) +
            BasicMath::square($this->getParentFactorGraph()->getGameInfo()->getDynamicsFactor()),
            $skillsVariable
        );
    }

    private function createSkillOutputVariable(Player $key): KeyedVariable
    {
        $parentFactorGraph = $this->getParentFactorGraph();
        $variableFactory = $parentFactorGraph->getVariableFactory();

        return $variableFactory->createKeyedVariable($key, $key . "'s skill");
    }
}
