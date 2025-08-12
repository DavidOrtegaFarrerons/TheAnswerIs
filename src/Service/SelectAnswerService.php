<?php

namespace App\Service;

use App\Entity\Game;
use App\Event\Game\AnswerSelectedEvent;
use App\Repository\GameRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SelectAnswerService
{

    public function __construct(
        private readonly EventDispatcherInterface $dispatcher,
    )
    {
    }

    public function selectAnswer(Game $game, string $answer)
    {
        $this->dispatcher->dispatch(new AnswerSelectedEvent($game, $answer));
    }
}
