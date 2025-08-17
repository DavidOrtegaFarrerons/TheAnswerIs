<?php

namespace App\Dto\Publisher\Shared;

use App\Enum\MercureEventType;

class OptionSubmittedDto
{
    private MercureEventType $type;
    private string $optionLetter;
    private bool $isCorrect;

    /**
     * @param MercureEventType $type
     * @param string $optionLetter
     * @param bool $isCorrect
     */
    public function __construct(MercureEventType $type, string $optionLetter, bool $isCorrect)
    {
        $this->type = $type;
        $this->optionLetter = $optionLetter;
        $this->isCorrect = $isCorrect;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type->value,
            'payload' => [
                'optionLetter' => $this->optionLetter,
                'isCorrect' => $this->isCorrect,
            ]
        ];
    }
}
