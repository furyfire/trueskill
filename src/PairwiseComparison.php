<?php

namespace DNW\Skills;

/**
 * Represents a comparison between two players.
 *
 * @internal The actual values for the enum were chosen so that the also correspond to the multiplier for updates to means.
 */
class PairwiseComparison
{
    final const WIN = 1;

    final const DRAW = 0;

    final const LOSE = -1;

    public static function getRankFromComparison($comparison)
    {
        return match ($comparison) {
            PairwiseComparison::WIN => [1, 2],
            PairwiseComparison::LOSE => [2, 1],
            default => [1, 1],
        };
    }
}
