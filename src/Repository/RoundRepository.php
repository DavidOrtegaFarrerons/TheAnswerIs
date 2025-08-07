<?php

namespace App\Repository;

use App\Entity\Contest;
use App\Entity\Game;
use App\Entity\Round;
use App\Enum\Difficulty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Round>
 */
class RoundRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly QuestionRepository $questionRepository,
    )
    {
        parent::__construct($registry, Round::class);
    }

    public function createRound(Contest $contest, Game $game): Round
    {
        $roundsSoFar = $game->getRounds()->count();

        $difficulty = match (true) {
            $roundsSoFar < 5   => Difficulty::EASY,
            $roundsSoFar < 10  => Difficulty::MEDIUM,
            default            => Difficulty::HARD,
        };

        $question = $this->questionRepository
            ->findQuestionByContestAndGameAndDifficulty($contest, $game, $difficulty);

        $round = new Round();
        $round->setQuestion($question);
        $round->setQuestionNumber($roundsSoFar + 1);
        $round->setStartedAt(new \DateTimeImmutable());

        $round->setGame($game);

        $this->getEntityManager()->persist($round);
        $this->getEntityManager()->flush();

        return $round;
    }

    public function findCurrentRoundByGame(Game $game) : ?Round
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.game = :game')
            ->setParameter('game', $game)
            ->orderBy('r.startedAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    //    /**
    //     * @return Round[] Returns an array of Round objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Round
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
