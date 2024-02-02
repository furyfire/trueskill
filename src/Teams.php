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
        $result = [];

        foreach ($args as $currentTeam) {
            $localCurrentTeam = $currentTeam;
            $result[] = $localCurrentTeam;
        }

        return $result;
    }
}
