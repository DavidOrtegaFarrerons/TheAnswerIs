<?php

namespace App\Enum;

enum Difficulty : string
{
    case EASY = 'easy';
    case MEDIUM = 'medium';
    case HARD = 'hard';

    public static function difficultyByRound(int $roundsSoFar) : Difficulty
    {
        return match (true) {
            $roundsSoFar < 5   => Difficulty::EASY,
            $roundsSoFar < 10  => Difficulty::MEDIUM,
            default            => Difficulty::HARD,
        };
    }
}
