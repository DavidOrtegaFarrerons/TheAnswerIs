<?php

namespace App\Dto\Publisher\Shared;

use App\Enum\MercureEventType;

class GameEndedDto
{
    private MercureEventType $type;

    /**
     * @param MercureEventType $type
     */
    public function __construct(MercureEventType $type)
    {
        $this->type = $type;
    }

    public function getType(): MercureEventType
    {
        return $this->type;
    }

    public function setType(MercureEventType $type): void
    {
        $this->type = $type;
    }

    public function toArray(): array
    {
        return ['type' => $this->type->value];
    }
}
