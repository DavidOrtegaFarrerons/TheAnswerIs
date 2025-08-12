<?php

namespace App\Service;

use App\Entity\Game;
use App\Event\Game\ContestantJoinedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

readonly class ContestantJoinsService
{

    public function __construct(
        private EventDispatcherInterface $dispatcher
    )
    {
    }

    public function join(Game $game) : void
    {
        $this->dispatcher->dispatch(new ContestantJoinedEvent($game->getId()));
    }
}
