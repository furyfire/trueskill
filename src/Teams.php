<?php

namespace DNW\Skills;

class Teams
{
    public static function concat(...$args/*variable arguments*/)
    {
        $result = [];

        foreach ($args as $currentTeam) {
            $localCurrentTeam = $currentTeam;
            $result[] = $localCurrentTeam;
        }

        return $result;
    }
}
