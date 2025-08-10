<?php

namespace App\Service;

use App\Dto\CreateGameDto;
use App\Entity\Game;
use App\Exception\ContestNotFoundException;
use App\Factory\GameFactory;
use App\Repository\ContestRepository;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class CreateGameService
{

    public function __construct(
        private GameFactory                 $gameFactory,
        private ContestRepository           $contestRepository,
        private EntityManagerInterface      $em,
        private GameRepository              $gameRepository,
    )
    {
    }

    public function create(CreateGameDto $dto): Game
    {
        return $this->em->wrapInTransaction(function () use ($dto) {
            $contest = $this->contestRepository->findOneBy(['id' => $dto->getContestId()]);

            if ($contest === null) {
                throw new ContestNotFoundException();
            }

            $game = $this->gameFactory->createFromContest($contest);
            $this->gameRepository->add($game);

            return $game;
        });
    }
}
