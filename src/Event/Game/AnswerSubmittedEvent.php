<?php

namespace App\Event\Game;

use App\Entity\Game;

class AnswerSubmittedEvent
{
    private Game $game;
    private string $option;
    private bool $isCorrect;

    /**
     * @param Game $game
     * @param string $option
     * @param bool $isCorrect
     */
    public function __construct(Game $game, string $option, bool $isCorrect)
    {
        $this->game = $game;
        $this->option = $option;
        $this->isCorrect = $isCorrect;
    }

    public function getOption(): string
    {
        return $this->option;
    }

    public function setOption(string $option): void
    {
        $this->option = $option;
    }

    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }

    public function setIsCorrect(bool $isCorrect): void
    {
        $this->isCorrect = $isCorrect;
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
