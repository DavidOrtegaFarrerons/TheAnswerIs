<?php

namespace App\TopicGenerator;

use Symfony\Component\Uid\Uuid;

class GameTopicGenerator
{
    public function generateForPresenter(Uuid $gameId, Uuid $presenterToken): string
    {
        return sprintf("/game/%s/%s", $gameId, $presenterToken);
    }

    public function generateForContestant(Uuid $gameId, Uuid $contestantToken): string
    {
        return sprintf("/game/%s/%s", $gameId, $contestantToken);
    }
}
