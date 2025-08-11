<?php

namespace App\Service\Token;

use Symfony\Component\Uid\Uuid;

interface TokenInterface
{
    public function generate(): Uuid;
    public function isValid(string $token): bool;
    public function fromString(string $token): Uuid;
}
