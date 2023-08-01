<?php

namespace DNW\Skills;

class RatingContainer
{
    private HashMap $_playerToRating;

    public function __construct()
    {
        $this->_playerToRating = new HashMap();
    }

    public function getRating(Player $player): mixed
    {
        return $this->_playerToRating->getValue($player);
    }

    public function setRating(Player $player, Rating $rating): HashMap
    {
        return $this->_playerToRating->setValue($player, $rating);
    }

    public function getAllPlayers(): array
    {
        return $this->_playerToRating->getAllKeys();
    }

    public function getAllRatings(): array
    {
        return $this->_playerToRating->getAllValues();
    }

    public function count(): int
    {
        return $this->_playerToRating->count();
    }
}
