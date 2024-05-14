<?php

declare(strict_types=1);

namespace DNW\Skills;

class Teams
{
    /**
     * @return Team[]
     */
    public static function concat(Team ...$args/*variable arguments*/): array
    {
        return $args;
    }
}
