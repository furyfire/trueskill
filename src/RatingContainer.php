<?php

declare(strict_types=1);

namespace DNW\Skills;

class RatingContainer
{
    private readonly HashMap $playerToRating;

    public function __construct()
    {
        $this->playerToRating = new HashMap();
    }

    public function getRating(Player $player): Rating
    {
        return $this->playerToRating->getValue($player);
    }

    public function setRating(Player $player, Rating $rating): HashMap
    {
        return $this->playerToRating->setValue($player, $rating);
    }

    /**
     * @return Player[]
     */
    public function getAllPlayers(): array
    {
        return $this->playerToRating->getAllKeys();
    }

    /**
     * @return Rating[]
     */
    public function getAllRatings(): array
    {
        return $this->playerToRating->getAllValues();
    }

    public function count(): int
    {
        return $this->playerToRating->count();
    }
}
