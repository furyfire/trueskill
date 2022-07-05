<?php

namespace DNW\Skills\FactorGraphs;

class VariableFactory
{
    public function __construct(private $_variablePriorInitializer)
    {
    }

    public function createBasicVariable($name)
    {
        $initializer = $this->_variablePriorInitializer;

        return new Variable($name, $initializer());
    }

    public function createKeyedVariable($key, $name)
    {
        $initializer = $this->_variablePriorInitializer;

        return new KeyedVariable($key, $name, $initializer());
    }
}
