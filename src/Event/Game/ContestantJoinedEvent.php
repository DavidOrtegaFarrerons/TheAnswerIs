<?php

namespace App\Event\Game;

use Symfony\Component\Uid\Uuid;

class ContestantJoinedEvent
{
    private Uuid $gameId;
    private \DateTimeImmutable $joinedAt;

    /**
     * @param Uuid $gameId
     */
    public function __construct(Uuid $gameId)
    {
        $this->gameId = $gameId;
        $this->joinedAt = new \DateTimeImmutable();
    }

    public function getGameId(): Uuid
    {
        return $this->gameId;
    }

    public function setGameId(Uuid $gameId): void
    {
        $this->gameId = $gameId;
    }

    public function getJoinedAt(): \DateTimeImmutable
    {
        return $this->joinedAt;
    }

    public function setJoinedAt(\DateTimeImmutable $joinedAt): void
    {
        $this->joinedAt = $joinedAt;
    }
}
