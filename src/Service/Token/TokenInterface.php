<?php

namespace App\Service\Token;

use Symfony\Component\Uid\Uuid;

interface TokenInterface
{
    public function generate(): Uuid;
}
