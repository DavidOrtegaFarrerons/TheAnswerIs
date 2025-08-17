<?php

namespace App\Builder;

use App\Dto\Publisher\Shared\JokerUsedDto;
use App\Enum\Audience;
use App\Enum\MercureEventType;
use App\Event\Game\JokerUsedEvent;

class JokerUsedPayloadBuilder implements GameEventPayloadBuilderInterface
{

    public function supports(object $event): bool
    {
        return $event instanceof JokerUsedEvent;
    }


    public function build(
        /** @var JokerUsedEvent $event */
        object $event,
        Audience $audience): array
    {
        return (new JokerUsedDto(
            MercureEventType::JOKER_USED,
            $event->getResult(),
        ))->toArray();
    }
}
