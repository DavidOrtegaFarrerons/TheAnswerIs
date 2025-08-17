<?php

namespace App\Dto\Publisher\Presenter;

use App\Enum\MercureEventType;

class OptionRevealedPresenterDto
{
    private MercureEventType $type;
    private string $optionLetter;
    private string $optionText;
    private bool $isCorrect;

    /**
     * @param MercureEventType $type
     * @param string $optionLetter
     * @param string $optionText
     * @param bool $isCorrect
     */
    public function __construct(MercureEventType $type, string $optionLetter, string $optionText, bool $isCorrect)
    {
        $this->type = $type;
        $this->optionLetter = $optionLetter;
        $this->optionText = $optionText;
        $this->isCorrect = $isCorrect;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type->value,
            'payload' => [
                'optionLetter' => $this->optionLetter,
                'optionText' => $this->optionText,
                'isCorrect' => $this->isCorrect,
            ]
        ];
    }


}
