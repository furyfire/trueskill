<?php

declare(strict_types=1);

namespace DNW\Skills\FactorGraphs;

abstract class Schedule implements \Stringable
{
    protected function __construct(private string $name)
    {
    }

    abstract public function visit(int $depth = -1, int $maxDepth = 0): float;

    public function __toString(): string
    {
        return $this->name;
    }
}
