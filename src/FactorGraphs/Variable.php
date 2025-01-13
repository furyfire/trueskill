<?php

declare(strict_types=1);

namespace DNW\Skills\FactorGraphs;

use DNW\Skills\Numerics\GaussianDistribution;

class Variable
{
    private mixed $value;

    public function __construct(private readonly GaussianDistribution $prior)
    {
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
}
