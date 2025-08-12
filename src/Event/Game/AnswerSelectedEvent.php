<?php

namespace App\Event\Game;

use App\Entity\Game;

class AnswerSelectedEvent
{
    private Game $game;
    private string $option;

    /**
     * @param Game $game
     * @param string $option
     */
    public function __construct(Game $game, string $option)
    {
        $this->game = $game;
        $this->option = $option;
    }

    public function getGame(): Game
    {
        return $this->game;
    }

    public function setGame(Game $game): void
    {
        $this->game = $game;
    }

    public function getOption(): string
    {
        return $this->option;
    }

    public function setOption(string $option): void
    {
        $this->option = $option;
    }
}
