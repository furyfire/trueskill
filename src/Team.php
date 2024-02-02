<?php

namespace DNW\Skills;

class Team extends RatingContainer
{
    public function __construct(Player $player = NULL, Rating $rating = NULL)
    {
        parent::__construct();

        if ($player && $rating) {
            $this->addPlayer($player, $rating);
        }
    }

    public function addPlayer(Player $player, Rating $rating): self
    {
        $this->setRating($player, $rating);

        return $this;
    }
}
