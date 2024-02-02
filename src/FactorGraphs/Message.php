<?php

declare(strict_types=1);

namespace DNW\Skills\FactorGraphs;

use DNW\Skills\Numerics\GaussianDistribution;

class Message implements \Stringable
{
    public function __construct(private GaussianDistribution $value, private string $name)
    {
    }

    public function getValue(): GaussianDistribution
    {
        return $this->value;
    }

    public function setValue(GaussianDistribution $value): void
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
