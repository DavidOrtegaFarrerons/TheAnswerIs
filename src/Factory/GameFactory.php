<?php

namespace App\Factory;

use App\Entity\Contest;
use App\Entity\Game;
use App\Service\Token\TokenInterface;

readonly class GameFactory
{

    public function __construct(private TokenInterface $token)
    {
    }

    public function createFromContest(Contest $contest): Game
    {
        return (new Game())
            ->setContest($contest)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setPublicToken($this->token->generate())
            ->setPresenterToken($this->token->generate())
            ->setFinished(false)
        ;
    }
}
