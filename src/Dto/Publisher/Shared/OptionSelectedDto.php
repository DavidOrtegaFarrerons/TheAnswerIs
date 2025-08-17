<?php

namespace App\Dto\Publisher\Shared;

use App\Enum\MercureEventType;

class OptionSelectedDto
{
    private MercureEventType $type;
    private string $optionLetter;

    /**
     * @param MercureEventType $type
     * @param string $optionLetter
     */
    public function __construct(MercureEventType $type, string $optionLetter)
    {
        $this->type = $type;
        $this->optionLetter = $optionLetter;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type->value,
            'payload' => [
                'optionLetter' => $this->optionLetter,
            ]
        ];
    }
}
