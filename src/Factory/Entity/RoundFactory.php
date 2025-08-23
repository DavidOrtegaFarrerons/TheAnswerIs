<?php

namespace App\Factory\Entity;

use App\Entity\Game;
use App\Entity\Question;
use App\Entity\Round;

class RoundFactory
{
    public function create(Game $game, Question $question)
    {
        $roundsSoFar = $game->getRounds()->count();

        $round = new Round();
        $round->setQuestion($question);
        $round->setQuestionNumber($roundsSoFar + 1);
        $round->setStartedAt(new \DateTimeImmutable());

        $round->setGame($game);

        return $round;
    }
}
