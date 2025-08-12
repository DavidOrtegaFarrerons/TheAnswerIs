<?php

namespace App\Event\Game;

use App\Entity\Game;

class AnswerRevealedEvent
{
    private Game $game;
    private string $option;
    private string $optionText;

    private bool $isCorrect;

    /**
     * @param Game $game
     * @param string $option
     * @param string $optionText
     * @param bool $isCorrect
     */
    public function __construct(Game $game, string $option, string $optionText, bool $isCorrect = false)
    {
        $this->game = $game;
        $this->option = $option;
        $this->optionText = $optionText;
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

    public function getOption(): string
    {
        return $this->option;
    }

    public function setOption(string $option): void
    {
        $this->option = $option;
    }

    public function getOptionText(): string
    {
        return $this->optionText;
    }

    public function setOptionText(string $optionText): void
    {
        $this->optionText = $optionText;
    }

    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }

    public function setIsCorrect(bool $isCorrect): void
    {
        $this->isCorrect = $isCorrect;
    }
}
