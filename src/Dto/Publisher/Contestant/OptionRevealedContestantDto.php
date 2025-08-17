<?php

namespace App\Dto\Publisher\Contestant;

use App\Enum\MercureEventType;

class OptionRevealedContestantDto
{
    private MercureEventType $type;
    private string $optionLetter;
    private string $optionText;

    /**
     * @param MercureEventType $type
     * @param string $optionLetter
     * @param string $optionText
     */
    public function __construct(MercureEventType $type, string $optionLetter, string $optionText)
    {
        $this->type = $type;
        $this->optionLetter = $optionLetter;
        $this->optionText = $optionText;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type->value,
            'payload' => [
                'optionLetter' => $this->optionLetter,
                'optionText' => $this->optionText,
            ]
        ];
    }


}
