<?php

namespace DNW\Skills\FactorGraphs;

class Variable implements \Stringable
{
    private $_name;

    private $_value;

    public function __construct($name, private $_prior)
    {
        $this->_name = 'Variable['.$name.']';
        $this->resetToPrior();
    }

    public function getValue()
    {
        return $this->_value;
    }

    public function setValue($value)
    {
        $this->_value = $value;
    }

    public function resetToPrior()
    {
        $this->_value = $this->_prior;
    }

    public function __toString(): string
    {
        return $this->_name;
    }
}
