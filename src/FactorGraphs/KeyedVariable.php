<?php

namespace DNW\Skills\FactorGraphs;

class KeyedVariable extends Variable
{
    public function __construct(private $_key, $name, $prior)
    {
        parent::__construct($name, $prior);
    }

    public function getKey()
    {
        return $this->_key;
    }
}
