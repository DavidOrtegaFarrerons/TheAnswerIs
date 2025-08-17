<?php

namespace App\Builder;

use App\Dto\Publisher\Contestant\OptionRevealedContestantDto;
use App\Dto\Publisher\Presenter\OptionRevealedPresenterDto;
use App\Enum\Audience;
use App\Enum\MercureEventType;
use App\Event\Game\OptionRevealedEvent;

class OptionRevealedPayloadBuilder implements GameEventPayloadBuilderInterface
{

    public function supports(object $event): bool
    {
        return $event instanceof OptionRevealedEvent;
    }


    public function build(
        /** @var OptionRevealedEvent $event */
        object $event,
        Audience $audience): array
    {
        return match ($audience) {
            Audience::PRESENTER => (new OptionRevealedPresenterDto(
                MercureEventType::OPTION_REVEALED,
                $event->getOptionLetter(),
                $event->getOptionText(),
                $event->isCorrect()
            ))->toArray(),

            Audience::CONTESTANT => (new OptionRevealedContestantDto(
                MercureEventType::OPTION_REVEALED,
                $event->getOptionLetter(),
                $event->getOptionText(),
            ))->toArray(),
        };
    }
}
