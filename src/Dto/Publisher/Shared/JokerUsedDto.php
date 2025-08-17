<?php

namespace App\Dto\Publisher\Shared;

use App\Enum\MercureEventType;

class JokerUsedDto
{
    private MercureEventType $type;
    private array $result;

    /**
     * @param MercureEventType $type
     * @param array $result
     */
    public function __construct(MercureEventType $type, array $result)
    {
        $this->type = $type;
        $this->result = $result;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type->value,
            'payload' => $this->result,
        ];
    }
}
