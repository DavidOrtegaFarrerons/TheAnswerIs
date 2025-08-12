<?php

namespace App\Service;

use App\Dto\StartContestantGameDto;
use App\Entity\Game;
use App\Repository\RoundRepository;

readonly class StartContestantGameService
{

    public function __construct(private RoundRepository $roundRepository)
    {
    }

    public function start(Game $game) : StartContestantGameDto
    {
        $round = $this->roundRepository->findCurrentRoundByGame($game);
        $roundsPlayed = $game->getRounds()->count() - 1;


        return new StartContestantGameDto(
            $game,
            $round,
            $roundsPlayed,
        );
    }
}
