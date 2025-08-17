<?php

namespace App\Service\Joker;

use App\Entity\Round;
use App\Enum\Joker;

class PhoneJoker implements JokerStrategyInterface
{
    public function supports(Joker $joker): bool
    {
        return $joker === Joker::PHONE;
    }

    public function apply(Round $round): array
    {
        return ['joker' => Joker::PHONE];
    }
}
