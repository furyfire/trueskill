<?php namespace DNW\Skills\TrueSkill\Layers;

use DNW\Skills\FactorGraphs\FactorGraphLayer;
use DNW\Skills\TrueSkill\TrueSkillFactorGraph;

abstract class TrueSkillFactorGraphLayer extends FactorGraphLayer
{
    public function __construct(TrueSkillFactorGraph $parentGraph)
    {
        parent::__construct($parentGraph);
    }
}