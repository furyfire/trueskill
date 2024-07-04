<?php

declare(strict_types=1);

namespace DNW\Skills\FactorGraphs;

class VariableFactory
{
    public function __construct(private \Closure $varPriorInitializer)
    {
    }

    public function createBasicVariable(): Variable
    {
        $initializer = $this->varPriorInitializer;

        return new Variable($initializer());
    }

    public function createKeyedVariable(mixed $key): KeyedVariable
    {
        $initializer = $this->varPriorInitializer;

        return new KeyedVariable($key, $initializer());
    }
}
