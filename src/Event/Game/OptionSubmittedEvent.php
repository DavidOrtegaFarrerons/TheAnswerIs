<?php

namespace App\Event\Game;

use Symfony\Component\Uid\Uuid;

class OptionSubmittedEvent implements GameEventInterface
{
    private Uuid $gameId;
    private Uuid $presenterToken;
    private Uuid $publicToken;
    private string $optionLetter;
    private bool $isCorrect;

    /**
     * @param Uuid $gameId
     * @param Uuid $presenterToken
     * @param Uuid $publicToken
     * @param string $optionLetter
     * @param bool $isCorrect
     */
    public function __construct(Uuid $gameId, Uuid $presenterToken, Uuid $publicToken, string $optionLetter, bool $isCorrect)
    {
        $this->gameId = $gameId;
        $this->presenterToken = $presenterToken;
        $this->publicToken = $publicToken;
        $this->optionLetter = $optionLetter;
        $this->isCorrect = $isCorrect;
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

    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }

    public function setIsCorrect(bool $isCorrect): void
    {
        $this->isCorrect = $isCorrect;
    }
}
