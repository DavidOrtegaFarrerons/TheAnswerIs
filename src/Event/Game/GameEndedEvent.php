<?php

namespace App\Event\Game;

use App\Entity\Game;

class GameEndedEvent
{
    private Game $game;

    /**
     * @param Game $game
     */
    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function getGame(): Game
    {
        return $this->game;
    }

    public function setGame(Game $game): void
    {
        $this->game = $game;
    }
}
