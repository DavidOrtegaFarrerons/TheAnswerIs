<?php

namespace App\Event\Game;

use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\EventDispatcher\Event;

class GameStartedEvent extends Event
{
    private Uuid $gameId;
    private Uuid $publicToken;
    private \DateTimeImmutable $ocurredAt;

    /**
     * @param Uuid $gameId
     * @param Uuid $publicToken
     */
    public function __construct(Uuid $gameId, Uuid $publicToken)
    {
        $this->gameId = $gameId;
        $this->publicToken = $publicToken;
        $this->ocurredAt = new \DateTimeImmutable();
    }

    public function getGameId(): Uuid
    {
        return $this->gameId;
    }

    public function setGameId(Uuid $gameId): void
    {
        $this->gameId = $gameId;
    }

    public function getPublicToken(): Uuid
    {
        return $this->publicToken;
    }

    public function setPublicToken(Uuid $publicToken): void
    {
        $this->publicToken = $publicToken;
    }

    public function getOcurredAt(): \DateTimeImmutable
    {
        return $this->ocurredAt;
    }

    public function setOcurredAt(\DateTimeImmutable $ocurredAt): void
    {
        $this->ocurredAt = $ocurredAt;
    }
}
