<?php

namespace App\Enum;

enum MercureEventType: string
{
    case GAME_STARTED = 'GAME_STARTED';
    case ANSWER_REVEALED = 'ANSWER_REVEALED';
    case ANSWER_SELECTED = 'ANSWER_SELECTED';
    case ANSWER_SUBMITTED = 'ANSWER_SUBMITTED';
    case END_OF_GAME = 'END_OF_GAME';
    case NEXT_ROUND = 'NEXT_ROUND';
}
