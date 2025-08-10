<?php

namespace App\Service\Token;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

class V4UuidTokenGenerator
{
    public function generate(): UuidV4
    {
        return Uuid::v4();
    }
}
