<?php

namespace App\Service\Token;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

class V4UuidTokenGenerator implements TokenInterface
{
    public function generate(): UuidV4
    {
        return new UuidV4();
    }

    public function isValid(string $token): bool
    {
        return Uuid::isValid($token);
    }

    public function fromString(string $token): Uuid
    {
        return Uuid::fromString($token);
    }
}
