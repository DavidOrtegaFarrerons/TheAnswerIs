<?php

namespace App\Publisher;

use App\Builder\GameEventPayloadBuilderInterface;
use App\Enum\Audience;
use App\TopicGenerator\GameTopicGenerator;
use Symfony\Component\HttpFoundation\Exception\LogicException;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Uid\Uuid;

class GameRealtimePublisher
{

    public function __construct(
        private iterable $builders,
        private GameTopicGenerator $topicGenerator,
        private HubInterface $hub,
    )
    {
    }

    public function publish(object $event, Uuid $gameId, Uuid $presenterToken, Uuid $publicToken) : void
    {
        $builder = $this->findBuilder($event);

        foreach (Audience::cases() as $audience) {
            $payload = $builder->build($event, $audience);

            $topic = match ($audience) {
                Audience::PRESENTER => $this->topicGenerator->generateForPresenter($gameId, $presenterToken),
                Audience::CONTESTANT => $this->topicGenerator->generateForContestant($gameId, $publicToken),
            };

            $this->hub->publish(new Update($topic, json_encode($payload)));
        }
    }

    private function findBuilder(object $event) : GameEventPayloadBuilderInterface
    {
        foreach ($this->builders as $builder) {
            if ($builder->supports($event)) {
                return $builder;
            }
        }

        throw new LogicException('No payload builder found for' . get_class($event));
    }
}
