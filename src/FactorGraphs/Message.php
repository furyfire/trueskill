<?php

namespace DNW\Skills\FactorGraphs;

class Message implements \Stringable
{
    public function __construct(private $_value = null, private $_name = null)
    {
    }

    public function getValue()
    {
        return $this->_value;
    }

    public function setValue($value)
    {
        $this->_value = $value;
    }

    public function __toString(): string
    {
        return (string) $this->_name;
    }
}
