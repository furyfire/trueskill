<?php

namespace DNW\Skills;

use DNW\Skills\Numerics\Range;

class TeamsRange extends Range
{
    protected static function create($min, $max)
    {
        return new TeamsRange($min, $max);
    }
}
