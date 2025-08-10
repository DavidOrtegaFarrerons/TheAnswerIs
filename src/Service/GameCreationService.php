<?php

namespace App\Service;

use App\Entity\Contest;
use App\Entity\Game;
use App\Exception\ContestNotFoundException;
use App\Factory\GameFactory;
use Doctrine\ORM\EntityManagerInterface;

readonly class GameCreationService
{

    public function __construct(
        private GameFactory            $gameFactory,
        private EntityManagerInterface $em
    )
    {
    }

    /**
     * @throws ContestNotFoundException
     */
    public function create(string $contestId): Game
    {
        $contest = $this->em->getRepository(Contest::class)->findOneBy(['id' => $contestId]);

        if ($contest === null) {
            throw new ContestNotFoundException();
        }

        $game = $this->gameFactory->createFromContest($contest);
        $this->em->persist($game);
        $this->em->flush();

        return $game;
    }
}
