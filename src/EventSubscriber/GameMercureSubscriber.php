<?php

namespace App\EventSubscriber;

use App\Event\Game\OptionRevealedEvent;
use App\Event\Game\OptionSelectedEvent;
use App\Event\Game\OptionSubmittedEvent;
use App\Event\Game\ContestantJoinedEvent;
use App\Event\Game\GameEndedEvent;
use App\Event\Game\GameStartedEvent;
use App\Event\Game\JokerUsedEvent;
use App\Event\Game\NextRoundEvent;
use App\Publisher\GameRealtimePublisher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class GameMercureSubscriber implements EventSubscriberInterface
{


    public function __construct(
        private readonly GameRealtimePublisher $publisher,
        private readonly HubInterface $hub
    )
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            ContestantJoinedEvent::class => [
                ['onContestantJoined', 0]
            ],
            GameStartedEvent::class => [
                ['onGameStarted', 0],
            ],
            OptionRevealedEvent::class => [
                ['onOptionRevealed', 0],
            ],
            OptionSelectedEvent::class => [
                ['onOptionSelected', 0],
            ],
            OptionSubmittedEvent::class => [
                ['onOptionSubmitted', 0],
            ],
            GameEndedEvent::class => [
                ['onGameEnded', 0],
            ],
            NextRoundEvent::class => [
                ['onNextRound', 0],
            ],
            JokerUsedEvent::class => [
                ['onJokerUsed', 0],
            ]
        ];
    }

    public function onContestantJoined(ContestantJoinedEvent $event) : void
    {
        $update = new Update(
            "/game/{$event->getGameId()}",
            json_encode([
                'type' => 'CONTESTANT_JOINED',
                'payload' => ['timestamp' => $event->getJoinedAt()],
            ])
        );

        $this->hub->publish($update);
    }

    public function onGameStarted(GameStartedEvent $event): void
    {
        $this->publisher->publish($event, $event->getGameId(), $event->getPresenterToken(), $event->getPublicToken());
    }

    public function onOptionRevealed(OptionRevealedEvent $event): void
    {
        $this->publisher->publish($event, $event->getGameId(), $event->getPresenterToken(), $event->getPublicToken());
    }

    public function onJokerUsed(JokerUsedEvent $event) : void
    {
        $this->publisher->publish($event, $event->getGameId(), $event->getPresenterToken(), $event->getPublicToken());
    }

    public function onOptionSelected(OptionSelectedEvent $event): void
    {
        $this->publisher->publish($event, $event->getGameId(), $event->getPresenterToken(), $event->getPublicToken());
    }

    public function onOptionSubmitted(OptionSubmittedEvent $event): void
    {
        $this->publisher->publish($event, $event->getGameId(), $event->getPresenterToken(), $event->getPublicToken());
    }

    public function onNextRound(NextRoundEvent $event): void
    {
        $this->publisher->publish($event, $event->getGameId(), $event->getPresenterToken(), $event->getPublicToken());
    }

    public function onGameEnded(GameEndedEvent $event): void
    {
        $this->publisher->publish($event, $event->getGameId(), $event->getPresenterToken(), $event->getPublicToken());
    }
}







