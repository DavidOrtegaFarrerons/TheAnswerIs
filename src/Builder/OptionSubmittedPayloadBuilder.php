<?php

namespace App\Builder;

use App\Dto\Publisher\Shared\OptionSubmittedDto;
use App\Enum\Audience;
use App\Enum\MercureEventType;
use App\Event\Game\OptionSubmittedEvent;

class OptionSubmittedPayloadBuilder implements GameEventPayloadBuilderInterface
{

    public function supports(object $event): bool
    {
        return $event instanceof OptionSubmittedEvent;
    }


    public function build(
        /** @var OptionSubmittedEvent $event */
        object $event,
        Audience $audience): array
    {
        return (new OptionSubmittedDto(
            MercureEventType::OPTION_SUBMITTED,
            $event->getOptionLetter(),
            $event->isCorrect()
        ))->toArray();
    }
}
