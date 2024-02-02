<?php

declare(strict_types=1);

namespace DNW\Skills\FactorGraphs;

class FactorGraph
{
    private VariableFactory $variableFactory;

    public function __construct(VariableFactory $factory)
    {
        $this->variableFactory = $factory;
    }

    public function getVariableFactory(): VariableFactory
    {
        return $this->variableFactory;
    }

    public function setVariableFactory(VariableFactory $factory): void
    {
        $this->variableFactory = $factory;
    }
}
