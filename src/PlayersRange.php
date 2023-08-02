<?php

namespace DNW\Skills;

use DNW\Skills\Numerics\Range;

class PlayersRange extends Range
{
    protected static function create(int $min, int $max): static
    {
        return new PlayersRange($min, $max);
    }
}
