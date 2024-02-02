<?php

declare(strict_types=1);

namespace DNW\Skills;

class PartialPlay
{
    public static function getPartialPlayPercentage(Player $player): float
    {
        $partialPlayPercentage = $player->getPartialPlayPercentage();

        // HACK to get around bug near 0
        $smallestPercentage = 0.0001;
        if ($partialPlayPercentage < $smallestPercentage) {
            $partialPlayPercentage = $smallestPercentage;
        }

        return $partialPlayPercentage;
    }
}
