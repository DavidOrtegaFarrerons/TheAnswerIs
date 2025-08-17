<?php

namespace App\Service\Joker;

use App\Entity\Round;
use App\Enum\Joker;

interface JokerStrategyInterface
{
    public function supports(Joker $joker): bool;
    public function apply(Round $round) : array;
}
