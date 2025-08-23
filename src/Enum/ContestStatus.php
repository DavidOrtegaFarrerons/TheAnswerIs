<?php

namespace App\Enum;

enum ContestStatus : string
{
    case PUBLISHED  = 'published';
    case DRAFT      = 'draft';
    case DELETED    = 'deleted';
}
