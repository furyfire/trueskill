<?php

declare(strict_types=1);

namespace DNW\Skills;

/**
 * Represents a player who has a Rating.
 */
class Player implements ISupportPartialPlay, ISupportPartialUpdate
{
    private const DEFAULT_PARTIAL_PLAY_PERCENTAGE = 1.0; // = 100% play time

    private const DEFAULT_PARTIAL_UPDATE_PERCENTAGE = 1.0;

    private readonly float $PartialPlayPct;

    private readonly float $PartialUpdatePct;

    /**
     * Constructs a player.
     *
     * @param string|int $Id                      The identifier for the player, such as a name.
     * @param float $partialPlayPct   The weight percentage to give this player when calculating a new rank.
     * @param float $partialUpdatePct Indicated how much of a skill update a player should receive where 0 represents no update and 1.0 represents 100% of the update.
     */
    public function __construct(
        private readonly mixed $Id,
        float $partialPlayPct = self::DEFAULT_PARTIAL_PLAY_PERCENTAGE,
        float $partialUpdatePct = self::DEFAULT_PARTIAL_UPDATE_PERCENTAGE
    )
    {
        Guard::argumentInRangeInclusive($partialPlayPct, 0.0, 1.0, 'partialPlayPercentage');
        Guard::argumentInRangeInclusive($partialUpdatePct, 0, 1.0, 'partialUpdatePercentage');
        $this->PartialPlayPct = $partialPlayPct;
        $this->PartialUpdatePct = $partialUpdatePct;
    }

    /**
     * The identifier for the player, such as a name.
     */
    public function getId(): mixed
    {
        return $this->Id;
    }

    /**
     * Indicates the percent of the time the player should be weighted where 0.0 indicates the player didn't play and 1.0 indicates the player played 100% of the time.
     */
    public function getPartialPlayPercentage(): float
    {
        return $this->PartialPlayPct;
    }

    /**
     * Indicated how much of a skill update a player should receive where 0.0 represents no update and 1.0 represents 100% of the update.
     */
    public function getPartialUpdatePercentage(): float
    {
        return $this->PartialUpdatePct;
    }
}
