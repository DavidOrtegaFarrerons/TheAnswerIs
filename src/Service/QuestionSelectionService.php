<?php

namespace App\Service;

use App\Entity\Game;
use App\Enum\Difficulty;
use App\Repository\QuestionRepository;

readonly class QuestionSelectionService
{

    public function __construct(private QuestionRepository $questionRepository)
    {
    }

    public function select(Game $game, int $roundsPlayed)
    {
        return $this->questionRepository->findQuestionByContestAndGameAndDifficulty(
            $game->getContest(),
            $game,
            Difficulty::difficultyByRound($roundsPlayed),
        );
    }
}
