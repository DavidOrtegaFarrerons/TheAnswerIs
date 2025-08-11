<?php

namespace App\Service\Token;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

class V4UuidTokenGenerator implements TokenInterface
{
    public function generate(): UuidV4
    {
        return Uuid::v4();
    }

    public function isValid(string $token): bool
    {
        return UuidV4::isValid($token);
    }

    public function fromString(string $token): Uuid
    {
        return UuidV4::fromString($token);
    }
}
