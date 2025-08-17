<?php

namespace App\Dto\Publisher\Shared;

use App\Enum\MercureEventType;

class NextRoundDto
{
    private MercureEventType $type;
    private string $questionTitle;
    private int $roundsPlayed;

    /**
     * @param MercureEventType $type
     * @param string $questionText
     * @param int $roundsPlayed
     */
    public function __construct(MercureEventType $type, string $questionText, int $roundsPlayed)
    {
        $this->type = $type;
        $this->questionTitle = $questionText;
        $this->roundsPlayed = $roundsPlayed;
    }

    public function getType(): MercureEventType
    {
        return $this->type;
    }

    public function setType(MercureEventType $type): void
    {
        $this->type = $type;
    }

    public function getQuestionTitle(): string
    {
        return $this->questionTitle;
    }

    public function setQuestionTitle(string $questionTitle): void
    {
        $this->questionTitle = $questionTitle;
    }

    public function getRoundsPlayed(): int
    {
        return $this->roundsPlayed;
    }

    public function setRoundsPlayed(int $roundsPlayed): void
    {
        $this->roundsPlayed = $roundsPlayed;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type->value,
            'payload' => [
                'questionText' => $this->questionTitle,
                'roundsPlayed' => $this->roundsPlayed,
            ]
        ];
    }
}
