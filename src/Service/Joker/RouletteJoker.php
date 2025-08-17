<?php

namespace App\Service\Joker;

use App\Entity\Round;
use App\Enum\Joker;

class RouletteJoker implements JokerStrategyInterface
{
    public function supports(Joker $joker): bool
    {
        return $joker === Joker::ROULETTE;
    }

    public function apply(Round $round): array
    {
        $wrong = array_diff(['a','b','c','d'], [$round->getQuestion()->getCorrectAnswer()]);
        shuffle($wrong);
        $count = random_int(0, 3);

        return [
            'remove' => array_slice($wrong, 0, $count),
            'count' => $count,
            'joker' => Joker::ROULETTE
        ];
    }
}
