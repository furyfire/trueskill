<?php

namespace DNW\Skills\FactorGraphs;

class FactorGraph
{
    private $variableFactory;

    public function getVariableFactory()
    {
        return $this->variableFactory;
    }

    public function setVariableFactory(VariableFactory $factory)
    {
        $this->variableFactory = $factory;
    }
}
