<?php

namespace App\Event\Game;

use Symfony\Component\Uid\Uuid;

class GameStartedEvent implements GameEventInterface
{
    private Uuid $gameId;
    private Uuid $presenterToken;
    private Uuid $publicToken;

    /**
     * @param Uuid $gameId
     * @param Uuid $presenterToken
     * @param Uuid $publicToken
     */
    public function __construct(Uuid $gameId, Uuid $presenterToken, Uuid $publicToken)
    {
        $this->gameId = $gameId;
        $this->presenterToken = $presenterToken;
        $this->publicToken = $publicToken;
    }

    public function getGameId(): Uuid
    {
        return $this->gameId;
    }

    public function setGameId(Uuid $gameId): void
    {
        $this->gameId = $gameId;
    }

    public function getPresenterToken(): Uuid
    {
        return $this->presenterToken;
    }

    public function setPresenterToken(Uuid $presenterToken): void
    {
        $this->presenterToken = $presenterToken;
    }

    public function getPublicToken(): Uuid
    {
        return $this->publicToken;
    }

    public function setPublicToken(Uuid $publicToken): void
    {
        $this->publicToken = $publicToken;
    }
}
