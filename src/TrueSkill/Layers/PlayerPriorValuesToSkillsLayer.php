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

    #[\Override]
    public function buildLayer(): void
    {
        $teams = $this->teams;
        foreach ($teams as $currentTeam) {
            $localCurrentTeam = $currentTeam;
            $currentTeamSkills = [];

            $curTeamAllPlayers = $localCurrentTeam->getAllPlayers();
            foreach ($curTeamAllPlayers as $curTeamPlayer) {
                $localCurTeamPlayer = $curTeamPlayer;
                $curTeamPlayerRating = $currentTeam->getRating($localCurTeamPlayer);
                $playerSkill = $this->createSkillOutputVariable($localCurTeamPlayer);
                $priorFactor = $this->createPriorFactor($curTeamPlayerRating, $playerSkill);
                $this->addLayerFactor($priorFactor);
                $currentTeamSkills[] = $playerSkill;
            }

            $outputVariablesGroups = &$this->getOutputVariablesGroups();
            $outputVariablesGroups[] = $currentTeamSkills;
        }
    }

    #[\Override]
    public function createPriorSchedule(): ?ScheduleSequence
    {
        $localFactors = $this->getLocalFactors();

        //All priors
        return $this->scheduleSequence(
            array_map(
                //Prior to Skill Step
                static fn($prior): ScheduleStep => new ScheduleStep($prior, 0),
                $localFactors
            )
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

        return $variableFactory->createKeyedVariable($key);
    }
}
