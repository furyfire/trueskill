<?php

declare(strict_types=1);

namespace DNW\Skills\FactorGraphs;

class KeyedVariable extends Variable
{
    public function __construct(private readonly mixed $key, mixed $prior)
    {
        parent::__construct($prior);
    }

    public function getKey(): mixed
    {
        return $this->key;
    }
}
