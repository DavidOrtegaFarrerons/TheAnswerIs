<?php

namespace App\Service\Joker;

use App\Entity\Game;
use App\Enum\Joker;
use App\Event\Game\JokerUsedEvent;
use App\Exception\JokerAlreadyUsedException;
use App\Repository\RoundRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UseJokerService
{
    /** @var JokerStrategyInterface[] */
    private iterable $strategies;

    /**
     * @param JokerStrategyInterface[] $strategies
     */
    public function __construct(
        iterable $strategies,
        private readonly RoundRepository $roundRepository,
        private readonly EntityManagerInterface $em,
        private readonly EventDispatcherInterface $dispatcher,
    )
    {
        $this->strategies = $strategies;
    }


    public function use(Joker $joker, Game $game): void
    {
        $round = $this->roundRepository->findCurrentRoundByGame($game);

        if ($round->hasUsed($joker)) {
           throw new JokerAlreadyUsedException("The joker $joker->value has already been used");
        }

        $result = $this->getStrategyFor($joker)->apply($round);

        $this->em->wrapInTransaction(function() use ($joker, $round) {
            $round->useJoker($joker);
        });

        $this->dispatcher->dispatch(
            new JokerUsedEvent(
                $game->getId(),
                $game->getPresenterToken(),
                $game->getPublicToken(),
                $result
            )
        );
    }

    private function getStrategyFor(Joker $joker): JokerStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($joker)) {
                return $strategy;
            }
        }

        throw new BadRequestHttpException("No strategy found for joker {$joker->value}");
    }
}
