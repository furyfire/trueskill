<?php

declare(strict_types=1);

namespace DNW\Skills;

final class Team extends RatingContainer
{
    public function __construct(?Player $player = NULL, ?Rating $rating = NULL)
    {
        parent::__construct();
        if (! $player instanceof Player) {
            return;
        }

        if (! $rating instanceof Rating) {
            return;
        }

        $this->addPlayer($player, $rating);
    }

    public function addPlayer(Player $player, Rating $rating): self
    {
        $this->setRating($player, $rating);

        return $this;
    }
}
