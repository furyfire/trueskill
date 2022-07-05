<?php

namespace DNW\Skills\TrueSkill\Layers;

use DNW\Skills\TrueSkill\DrawMargin;
use DNW\Skills\TrueSkill\Factors\GaussianGreaterThanFactor;
use DNW\Skills\TrueSkill\Factors\GaussianWithinFactor;
use DNW\Skills\TrueSkill\TrueSkillFactorGraph;

class TeamDifferencesComparisonLayer extends TrueSkillFactorGraphLayer
{
    private $_epsilon;

    public function __construct(TrueSkillFactorGraph $parentGraph, private readonly array $_teamRanks)
    {
        parent::__construct($parentGraph);
        $gameInfo = $this->getParentFactorGraph()->getGameInfo();
        $this->_epsilon = DrawMargin::getDrawMarginFromDrawProbability($gameInfo->getDrawProbability(), $gameInfo->getBeta());
    }

    public function buildLayer()
    {
        $inputVarGroups = $this->getInputVariablesGroups();
        $inputVarGroupsCount = is_countable($inputVarGroups) ? count($inputVarGroups) : 0;

        for ($i = 0; $i < $inputVarGroupsCount; $i++) {
            $isDraw = ($this->_teamRanks[$i] == $this->_teamRanks[$i + 1]);
            $teamDifference = $inputVarGroups[$i][0];

            $factor =
                $isDraw
                    ? new GaussianWithinFactor($this->_epsilon, $teamDifference)
                    : new GaussianGreaterThanFactor($this->_epsilon, $teamDifference);

            $this->addLayerFactor($factor);
        }
    }
}
