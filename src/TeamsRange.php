<?php

declare(strict_types=1);

namespace DNW\Skills;

use DNW\Skills\Numerics\Range;

class TeamsRange extends Range
{
    protected static function create(int $min, int $max): static
    {
        return new static($min, $max);
    }
}
