<?php

namespace App\Service;

use App\Dto\StartPresenterGameDto;
use App\Entity\Game;
use App\Enum\Difficulty;
use App\Event\Game\GameStartedEvent;
use App\Factory\RoundFactory;
use App\Repository\QuestionRepository;
use App\Repository\RoundRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

readonly class StartPresenterGameService
{

    public function __construct(
        private QuestionSelectionService $questionSelectionService,
        private RoundFactory $roundFactory,
        private RoundRepository $roundRepository,
        private EntityManagerInterface $em,
        private EventDispatcherInterface $dispatcher,
    )
    {
    }

    public function start(Game $game)
    {
        $roundsPlayed = $game->getRounds()->count();
        if ($roundsPlayed === 0) {
            $round = $this->em->wrapInTransaction(function() use($game, $roundsPlayed) {
                $question = $this->questionSelectionService->select($game, $roundsPlayed);
                $round = $this->roundFactory->create($game, $question);
                $this->roundRepository->add($round);

                return $round;
            });
        } else {
            $round = $this->roundRepository->findCurrentRoundByGame($game);
            $roundsPlayed--;
        }

        $this->dispatcher->dispatch(
            new GameStartedEvent(
                $game->getId(),
                $game->getPresenterToken(),
                $game->getPublicToken()
            )
        );

        return new StartPresenterGameDto($game, $round, $roundsPlayed);
    }
}
