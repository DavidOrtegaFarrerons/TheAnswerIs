<?php

namespace App\Event\Game;

use App\Entity\Game;

class JokerUsedEvent
{
    private Game $game;
    private array $result;

    /**
     * @param Game $game
     * @param array $result
     */
    public function __construct(Game $game, array $result)
    {
        $this->game = $game;
        $this->result = $result;
    }

    public function getGame(): Game
    {
        return $this->game;
    }

    public function setGame(Game $game): void
    {
        $this->game = $game;
    }

    public function getResult(): array
    {
        return $this->result;
    }

    public function setResult(array $result): void
    {
        $this->result = $result;
    }
}
