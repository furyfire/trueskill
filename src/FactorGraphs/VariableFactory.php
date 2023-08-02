<?php

namespace DNW\Skills\FactorGraphs;

class VariableFactory
{
    public function __construct(private \Closure $variablePriorInitializer)
    {
    }

    public function createBasicVariable(string $name): Variable
    {
        $initializer = $this->variablePriorInitializer;

        return new Variable($name, $initializer());
    }

    public function createKeyedVariable(mixed $key, string $name): KeyedVariable
    {
        $initializer = $this->variablePriorInitializer;

        return new KeyedVariable($key, $name, $initializer());
    }
}
