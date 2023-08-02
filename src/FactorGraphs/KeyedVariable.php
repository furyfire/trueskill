<?php

namespace DNW\Skills\FactorGraphs;

class KeyedVariable extends Variable
{
    public function __construct(private mixed $key, string $name, mixed $prior)
    {
        parent::__construct($name, $prior);
    }

    public function getKey(): mixed
    {
        return $this->key;
    }
}
