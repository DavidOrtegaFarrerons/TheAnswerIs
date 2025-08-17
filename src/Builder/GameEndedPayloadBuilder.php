<?php

namespace App\Builder;

use App\Dto\Publisher\Shared\GameEndedDto;
use App\Enum\Audience;
use App\Enum\MercureEventType;
use App\Event\Game\GameEndedEvent;

class GameEndedPayloadBuilder implements GameEventPayloadBuilderInterface
{

    public function supports(object $event): bool
    {
        return $event instanceof GameEndedEvent;
    }


    public function build(
        /** @var GameEndedEvent $event */
        object $event,
        Audience $audience): array
    {
        return (new GameEndedDto(
            MercureEventType::END_OF_GAME,
        ))->toArray();
    }
}
