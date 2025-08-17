<?php

namespace App\Builder;

use App\Dto\Publisher\Shared\OptionSelectedDto;
use App\Enum\Audience;
use App\Enum\MercureEventType;
use App\Event\Game\OptionSelectedEvent;

class OptionSelectedPayloadBuilder implements GameEventPayloadBuilderInterface
{

    public function supports(object $event): bool
    {
        return $event instanceof OptionSelectedEvent;
    }

    public function build(
        /** @var OptionSelectedEvent $event */
        object $event,
        Audience $audience
    ): array
    {
        return (new OptionSelectedDto(MercureEventType::OPTION_SELECTED, $event->getOptionLetter()))->toArray();
    }
}
