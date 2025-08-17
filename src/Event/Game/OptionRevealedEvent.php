<?php

namespace App\Event\Game;

use Symfony\Component\Uid\Uuid;

class OptionRevealedEvent implements GameEventInterface
{
    private Uuid $gameId;
    private Uuid $presenterToken;
    private Uuid $publicToken;
    private string $optionLetter;
    private string $optionText;

    private bool $isCorrect;

    /**
     * @param Uuid $gameId
     * @param Uuid $presenterToken
     * @param Uuid $publicToken
     * @param string $option
     * @param string $optionText
     * @param bool $isCorrect
     */
    public function __construct(Uuid $gameId, Uuid $presenterToken, Uuid $publicToken, string $option, string $optionText, bool $isCorrect)
    {
        $this->gameId = $gameId;
        $this->presenterToken = $presenterToken;
        $this->publicToken = $publicToken;
        $this->optionLetter = $option;
        $this->optionText = $optionText;
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

    public function getOptionText(): string
    {
        return $this->optionText;
    }

    public function setOptionText(string $optionText): void
    {
        $this->optionText = $optionText;
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
