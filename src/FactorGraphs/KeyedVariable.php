<?php

declare(strict_types=1);

namespace DNW\Skills\FactorGraphs;

class KeyedVariable extends Variable
{
    public function __construct(private readonly mixed $key, string $name, mixed $prior)
    {
        parent::__construct($name, $prior);
    }

    public function getKey(): mixed
    {
        return $this->key;
    }
}
