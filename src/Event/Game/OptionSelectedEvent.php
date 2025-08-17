<?php

namespace App\Event\Game;

use Symfony\Component\Uid\Uuid;

class OptionSelectedEvent implements GameEventInterface
{
    private Uuid $gameId;
    private Uuid $presenterToken;
    private Uuid $publicToken;

    private string $optionLetter;

    /**
     * @param Uuid $gameId
     * @param Uuid $presenterToken
     * @param Uuid $publicToken
     * @param string $option
     */
    public function __construct(Uuid $gameId, Uuid $presenterToken, Uuid $publicToken, string $option)
    {
        $this->gameId = $gameId;
        $this->presenterToken = $presenterToken;
        $this->publicToken = $publicToken;
        $this->optionLetter = $option;
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

    public function getOptionLetter(): string
    {
        return $this->optionLetter;
    }

    public function setOptionLetter(string $optionLetter): void
    {
        $this->optionLetter = $optionLetter;
    }
}
