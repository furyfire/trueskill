<?php

declare(strict_types=1);

namespace DNW\Skills\TrueSkill\Layers;

use DNW\Skills\TrueSkill\DrawMargin;
use DNW\Skills\TrueSkill\Factors\GaussianGreaterThanFactor;
use DNW\Skills\TrueSkill\Factors\GaussianWithinFactor;
use DNW\Skills\TrueSkill\TrueSkillFactorGraph;

final class TeamDifferencesComparisonLayer extends TrueSkillFactorGraphLayer
{
    private readonly float $epsilon;

    /**
     * @param int[] $teamRanks
     */
    public function __construct(TrueSkillFactorGraph $parentGraph, private readonly array $teamRanks)
    {
        parent::__construct($parentGraph);
        $gameInfo = $this->getParentFactorGraph()->getGameInfo();
        $this->epsilon = DrawMargin::getDrawMarginFromDrawProbability($gameInfo->getDrawProbability(), $gameInfo->getBeta());
    }

    #[\Override]
    public function buildLayer(): void
    {
        $inputVarGroups = $this->getInputVariablesGroups();
        $inputVarGroupsCount = count($inputVarGroups);

        for ($i = 0; $i < $inputVarGroupsCount; ++$i) {
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
