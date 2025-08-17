<?php

namespace App\Builder;

use App\Dto\Publisher\Shared\NextRoundDto;
use App\Enum\Audience;
use App\Enum\MercureEventType;
use App\Event\Game\NextRoundEvent;

class NextRoundPayloadBuilder implements GameEventPayloadBuilderInterface
{

    public function supports(object $event): bool
    {
        return $event instanceof NextRoundEvent;
    }


    public function build(
        /** @var NextRoundEvent $event */
        object $event,
        Audience $audience): array
    {
        return (new NextRoundDto(
            MercureEventType::NEXT_ROUND,
            $event->getQuestionTitle(),
            $event->getRoundsPlayed()
        ))->toArray();
    }
}
