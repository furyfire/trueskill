<?php

namespace DNW\Skills\FactorGraphs;

class VariableFactory
{
    public function __construct(private $_variablePriorInitializer)
    {
    }

    public function createBasicVariable(string $name): Variable
    {
        $initializer = $this->_variablePriorInitializer;

        return new Variable($name, $initializer());
    }

    public function createKeyedVariable(mixed $key, string $name): KeyedVariable
    {
        $initializer = $this->_variablePriorInitializer;

        return new KeyedVariable($key, $name, $initializer());
    }
}
