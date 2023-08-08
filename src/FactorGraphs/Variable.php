<?php

namespace DNW\Skills\FactorGraphs;

use DNW\Skills\Numerics\GaussianDistribution;

class Variable implements \Stringable
{
    private string $name;

    private mixed $value;

    public function __construct(string $name, private GaussianDistribution $prior)
    {
        $this->name = 'Variable[' . $name . ']';
        $this->resetToPrior();
    }

    public function getValue(): GaussianDistribution
    {
        return $this->value;
    }

    public function setValue(GaussianDistribution $value): void
    {
        $this->value = $value;
    }

    public function resetToPrior(): void
    {
        $this->value = $this->prior;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
