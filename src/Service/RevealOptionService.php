<?php

namespace App\Service;

use App\Entity\Game;
use App\Event\Game\OptionRevealedEvent;
use App\Repository\RoundRepository;
use Psr\EventDispatcher\EventDispatcherInterface;

class RevealOptionService
{

    public function __construct(
        private readonly RoundRepository $roundRepository,
        private readonly EventDispatcherInterface $dispatcher,
    )
    {
    }

    public function revealAnswer(Game $game, string $option): void
    {
        $round = $this->roundRepository->findCurrentRoundByGame($game);
        $optionText = $round->getQuestion()->getOptionText($option);
        $isCorrectAnswer = $round->getQuestion()->getCorrectAnswer() === $option;

        $this->dispatcher->dispatch(
            new OptionRevealedEvent(
                $game->getId(),
                $game->getPresenterToken(),
                $game->getPublicToken(),
                $option,
                $optionText,
                $isCorrectAnswer
            )
        );
    }
}
