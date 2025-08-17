<?php

namespace App\Service;

use App\Entity\Game;
use App\Event\Game\OptionSubmittedEvent;
use App\Repository\RoundRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SubmitAnswerService
{

    public function __construct(
        private readonly RoundRepository $roundRepository,
        private readonly EventDispatcherInterface $dispatcher,
    )
    {
    }

    public function submitAnswer(Game $game, string $option) {
        $round = $this->roundRepository->findCurrentRoundByGame($game);
        $isCorrect = $option === $round->getQuestion()->getCorrectAnswer();

        $this->dispatcher->dispatch(
            new OptionSubmittedEvent(
                $game->getId(),
                $game->getPresenterToken(),
                $game->getPublicToken(),
                $option,
                $isCorrect
            )
        );

    }
}
