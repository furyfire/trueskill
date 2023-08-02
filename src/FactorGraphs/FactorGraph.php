<?php

namespace DNW\Skills\FactorGraphs;

class FactorGraph
{
    private VariableFactory $variableFactory;

    public function getVariableFactory(): VariableFactory
    {
        return $this->variableFactory;
    }

    public function setVariableFactory(VariableFactory $factory): void
    {
        $this->variableFactory = $factory;
    }
}
