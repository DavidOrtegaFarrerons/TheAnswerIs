<?php

namespace App\Event\Game;

use App\Entity\Game;

class NextRoundEvent
{
    private Game $game;
    private string $questionTitle;
    private string $roundsPlayed;

    /**
     * @param Game $game
     * @param string $questionTitle
     * @param string $roundsPlayed
     */
    public function __construct(Game $game, string $questionTitle, string $roundsPlayed)
    {
        $this->game = $game;
        $this->questionTitle = $questionTitle;
        $this->roundsPlayed = $roundsPlayed;
    }

    public function getGame(): Game
    {
        return $this->game;
    }

    public function setGame(Game $game): void
    {
        $this->game = $game;
    }

    public function getQuestionTitle(): string
    {
        return $this->questionTitle;
    }

    public function setQuestionTitle(string $questionTitle): void
    {
        $this->questionTitle = $questionTitle;
    }

    public function getRoundsPlayed(): string
    {
        return $this->roundsPlayed;
    }

    public function setRoundsPlayed(string $roundsPlayed): void
    {
        $this->roundsPlayed = $roundsPlayed;
    }
}
