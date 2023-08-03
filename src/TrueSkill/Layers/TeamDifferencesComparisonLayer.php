<?php

namespace DNW\Skills\TrueSkill\Layers;

use DNW\Skills\TrueSkill\DrawMargin;
use DNW\Skills\TrueSkill\Factors\GaussianGreaterThanFactor;
use DNW\Skills\TrueSkill\Factors\GaussianWithinFactor;
use DNW\Skills\TrueSkill\TrueSkillFactorGraph;

class TeamDifferencesComparisonLayer extends TrueSkillFactorGraphLayer
{
    private float $epsilon;

    /**
     * @param int[] $teamRanks
     */
    public function __construct(TrueSkillFactorGraph $parentGraph, private readonly array $teamRanks)
    {
        parent::__construct($parentGraph);
        $gameInfo = $this->getParentFactorGraph()->getGameInfo();
        $this->epsilon = DrawMargin::getDrawMarginFromDrawProbability($gameInfo->getDrawProbability(), $gameInfo->getBeta());
    }

    public function buildLayer(): void
    {
        $inputVarGroups = $this->getInputVariablesGroups();
        $inputVarGroupsCount = is_countable($inputVarGroups) ? count($inputVarGroups) : 0;

        for ($i = 0; $i < $inputVarGroupsCount; $i++) {
            $isDraw = ($this->teamRanks[$i] == $this->teamRanks[$i + 1]);
            $teamDifference = $inputVarGroups[$i][0];

            $factor =
                $isDraw
                    ? new GaussianWithinFactor($this->epsilon, $teamDifference)
                    : new GaussianGreaterThanFactor($this->epsilon, $teamDifference);

            $this->addLayerFactor($factor);
        }
    }
}
