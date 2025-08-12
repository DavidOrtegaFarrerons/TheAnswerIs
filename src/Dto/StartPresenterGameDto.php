<?php

namespace App\Dto;

use App\Entity\Game;
use App\Entity\Round;

class StartPresenterGameDto
{
    private Game $game;
    private Round $round;
    private int $roundsPlayed;

    /**
     * @param Game $game
     * @param Round $round
     * @param int $roundsPlayed
     */
    public function __construct(Game $game, Round $round, int $roundsPlayed)
    {
        $this->game = $game;
        $this->round = $round;
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

    public function getRound(): Round
    {
        return $this->round;
    }

    public function setRound(Round $round): void
    {
        $this->round = $round;
    }

    public function getRoundsPlayed(): int
    {
        return $this->roundsPlayed;
    }

    public function setRoundsPlayed(int $roundsPlayed): void
    {
        $this->roundsPlayed = $roundsPlayed;
    }
}
