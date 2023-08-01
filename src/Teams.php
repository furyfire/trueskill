<?php

namespace DNW\Skills;

class Teams
{
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
