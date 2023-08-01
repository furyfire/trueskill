<?php

namespace DNW\Skills;

use DNW\Skills\Numerics\Range;

class TeamsRange extends Range
{
    protected static function create(int $min, int $max): self
    {
        return new TeamsRange($min, $max);
    }
}
