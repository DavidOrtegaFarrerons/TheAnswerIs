<?php

namespace App\Service;

use App\Entity\Game;
use App\Event\Game\GameEndedEvent;
use App\Event\Game\NextRoundEvent;
use App\Factory\Entity\RoundFactory;
use App\Repository\RoundRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

readonly class NextRoundService
{

    public function __construct(
        private RoundFactory             $roundFactory,
        private RoundRepository          $roundRepository,
        private QuestionSelectionService $questionSelectionService,
        private EventDispatcherInterface $dispatcher,
        private EntityManagerInterface   $em,
    )
    {
    }

    public function nextRound(Game $game) : void
    {
        $roundsPlayed = $game->getRounds()->count();
        if ($game->getContest()->getTotalQuestions() <= $game->getRounds()->count()) {
            $this->dispatcher->dispatch(
                new GameEndedEvent(
                    $game->getId(),
                    $game->getPresenterToken(),
                    $game->getPublicToken()
                )
            );

            return;
        }

        $round = $this->em->wrapInTransaction(function() use ($game, $roundsPlayed) {
            $question = $this->questionSelectionService->select($game, $roundsPlayed);
            $round = $this->roundFactory->create($game, $question);
            $this->roundRepository->add($round);
            $this->em->flush();
            return $round;
        });

        $this->dispatcher->dispatch(
            new NextRoundEvent(
                $game->getId(),
                $game->getPresenterToken(),
                $game->getPublicToken(),
                $round->getQuestion()->getTitle(),
                $roundsPlayed
            )
        );
    }
}
