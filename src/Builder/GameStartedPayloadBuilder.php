<?php

namespace App\Builder;

use App\Dto\Publisher\Shared\GameStartedDto;
use App\Enum\Audience;
use App\Enum\MercureEventType;
use App\Event\Game\GameStartedEvent;

class GameStartedPayloadBuilder implements GameEventPayloadBuilderInterface
{

    public function supports(object $event): bool
    {
        return $event instanceof GameStartedEvent;
    }


    public function build(
        /** @var GameStartedEvent $event */
        object $event,
        Audience $audience): array
    {
        return (new GameStartedDto(MercureEventType::GAME_STARTED))->toArray();
    }
}
