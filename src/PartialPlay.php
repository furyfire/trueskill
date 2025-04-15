<?php

declare(strict_types=1);

namespace DNW\Skills;

final class PartialPlay
{
    public static function getPartialPlayPercentage(Player $player): float
    {
        $partialPlayPct = $player->getPartialPlayPercentage();

        // HACK to get around bug near 0
        $smallestPct = 0.0001;
        if ($partialPlayPct < $smallestPct) {
            return $smallestPct;
        }

        return $partialPlayPct;
    }
}
