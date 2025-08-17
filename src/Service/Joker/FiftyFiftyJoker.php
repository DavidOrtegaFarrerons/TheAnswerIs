<?php

namespace App\Service\Joker;

use App\Entity\Round;
use App\Enum\Joker;

class FiftyFiftyJoker implements JokerStrategyInterface
{
    public function supports(Joker $joker): bool
    {
        return $joker === Joker::FIFTY_FIFTY;
    }


    public function apply(Round $round): array
    {
        $wrong = array_diff(['a','b','c','d'], [$round->getQuestion()->getCorrectAnswer()]);
        shuffle($wrong);
        return [
            'remove' => array_slice($wrong, 0, 2),
            'joker' => Joker::FIFTY_FIFTY
        ];
    }
}
