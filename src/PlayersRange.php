<?php

namespace DNW\Skills;

use DNW\Skills\Numerics\Range;

class PlayersRange extends Range
{
    protected static function create($min, $max)
    {
        return new PlayersRange($min, $max);
    }
}
