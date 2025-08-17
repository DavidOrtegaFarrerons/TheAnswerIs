<?php

namespace App\Enum;

enum MercureEventType: string
{
    case GAME_STARTED = 'GAME_STARTED';
    case OPTION_REVEALED = 'OPTION_REVEALED';
    case JOKER_USED = 'JOKER_USED';
    case OPTION_SELECTED = 'OPTION_SELECTED';
    case OPTION_SUBMITTED = 'OPTION_SUBMITTED';
    case END_OF_GAME = 'END_OF_GAME';
    case NEXT_ROUND = 'NEXT_ROUND';
}
