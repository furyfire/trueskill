<?php

declare(strict_types=1);

namespace DNW\Skills\FactorGraphs;

abstract class FactorGraph
{
    private VariableFactory $variableFactory;

    protected function __construct()
    {
        $this->variableFactory = new VariableFactory(fn () => NULL);
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
