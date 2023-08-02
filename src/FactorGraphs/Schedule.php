<?php

namespace DNW\Skills\FactorGraphs;

abstract class Schedule implements \Stringable
{
    protected function __construct(private string $name)
    {
    }

    abstract public function visit(int $depth = -1, int $maxDepth = 0);

    public function __toString(): string
    {
        return (string) $this->name;
    }
}
