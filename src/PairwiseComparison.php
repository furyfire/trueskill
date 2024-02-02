<?php

declare(strict_types=1);

namespace DNW\Skills;

/**
 * Represents a comparison between two players.
 *
 * @internal The actual values for the enum were chosen so
 *           that the also correspond to the multiplier for updates to means.
 */
enum PairwiseComparison: int
{
    case WIN = 1;

    case DRAW = 0;

    case LOSE = -1;
}
