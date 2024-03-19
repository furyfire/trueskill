<?php

declare(strict_types=1);

namespace DNW\Skills\FactorGraphs;

class VariableFactory
{
    public function __construct(private \Closure $variablePriorInitializer)
    {
    }

    public function createBasicVariable(): Variable
    {
        $initializer = $this->variablePriorInitializer;

        return new Variable($initializer());
    }

    public function createKeyedVariable(mixed $key): KeyedVariable
    {
        $initializer = $this->variablePriorInitializer;

        return new KeyedVariable($key, $initializer());
    }
}
