<?php

namespace App\EventSubscriber;

use App\Enum\MercureEventType;
use App\Event\Game\AnswerRevealedEvent;
use App\Event\Game\AnswerSelectedEvent;
use App\Event\Game\AnswerSubmittedEvent;
use App\Event\Game\ContestantJoinedEvent;
use App\Event\Game\GameEndedEvent;
use App\Event\Game\GameStartedEvent;
use App\Event\Game\NextRoundEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class GameMercureSubscriber implements EventSubscriberInterface
{


    public function __construct(private readonly HubInterface $hub)
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
            AnswerRevealedEvent::class => [
                ['onAnswerRevealed', 0],
            ],
            AnswerSelectedEvent::class => [
                ['onAnswerSelected', 0],
            ],
            AnswerSubmittedEvent::class => [
                ['onAnswerSubmitted', 0],
            ],
            GameEndedEvent::class => [
                ['onGameEnded', 0],
            ],
            NextRoundEvent::class => [
                ['onNextRound', 0],
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
        $this->hub->publish(new Update(
            "/game/{$event->getGameId()}/{$event->getPublicToken()}",
            json_encode([
                'type' => MercureEventType::GAME_STARTED,
                'payload' => []
            ])
        ));
    }

    public function onAnswerRevealed(AnswerRevealedEvent $event): void
    {
        $payload = [
            'type' => MercureEventType::ANSWER_REVEALED,
            'payload' => [
                'key' => $event->getOption(),
                'text' => $event->getOptionText(),
                'correct' => $event->isCorrect()
            ]
        ];

        $this->hub->publish(new Update(
            "/game/{$event->getGame()->getId()}/{$event->getGame()->getPresenterToken()}",
            json_encode($payload)
        ));
    }

    public function onAnswerSelected(AnswerSelectedEvent $event): void
    {
        $payload = [
            'type' => MercureEventType::ANSWER_SELECTED,
            'payload' => [
                'key' => $event->getOption()
            ]
        ];

        $this->hub->publish(new Update(
            "/game/{$event->getGame()->getId()}/{$event->getGame()->getPresenterToken()}",
            json_encode($payload)
        ));
    }

    public function onAnswerSubmitted(AnswerSubmittedEvent $event): void
    {
        $payload = [
            'type' => MercureEventType::ANSWER_SUBMITTED,
            'payload' => [
                'option' => $event->getOption(),
                'correct' => $event->isCorrect(),
            ]
        ];

        $this->hub->publish(new Update(
            "/game/{$event->getGame()->getId()}/{$event->getGame()->getPresenterToken()}",
            json_encode($payload)
        ));
    }

    public function onGameEnded(GameEndedEvent $event): void
    {
        $this->hub->publish(new Update(
            "/game/{$event->getGame()->getId()}/{$event->getGame()->getPresenterToken()}",
            json_encode([
                'type' => MercureEventType::END_OF_GAME,
                'payload' => []
            ])
        ));
    }

    public function onNextRound(NextRoundEvent $event): void
    {
        $payload = [
            'type' => MercureEventType::NEXT_ROUND,
            'payload' => [
                'questionText' => $event->getQuestionTitle(),
                'roundsPlayed' => $event->getRoundsPlayed(),
            ]
        ];

        $this->hub->publish(new Update(
            "/game/{$event->getGame()->getId()}/{$event->getGame()->getPresenterToken()}",
            json_encode($payload)
        ));
    }
}







