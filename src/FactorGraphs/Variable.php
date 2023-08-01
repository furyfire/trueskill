<?php

namespace DNW\Skills\FactorGraphs;

class Variable implements \Stringable
{
    private string $_name;

    private mixed $_value;

    public function __construct(string $name, private mixed $_prior)
    {
        $this->_name = 'Variable['.$name.']';
        $this->resetToPrior();
    }

    public function getValue(): mixed
    {
        return $this->_value;
    }

    public function setValue(mixed $value): void
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
