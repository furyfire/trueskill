<?php

declare(strict_types=1);

namespace DNW\Skills\FactorGraphs;

use DNW\Skills\Numerics\GaussianDistribution;

final class Message
{
    public function __construct(private GaussianDistribution $value)
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
}
