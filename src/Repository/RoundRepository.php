<?php

namespace App\Repository;

use App\Entity\Contest;
use App\Entity\Game;
use App\Entity\Round;
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
        $round = new Round();
        $round->setGame($game);
        $round->setQuestion($this->questionRepository->findQuestionByContestAndGame($contest, $game));
        $round->setQuestionNumber($game->getRounds()->count() + 1);
        $round->setStartedAt(new \DateTimeImmutable());
        $this->getEntityManager()->persist($round);
        $this->getEntityManager()->flush();

        return $round;
    }

    public function findCurrentRoundByGame(Game $game) : ?Round
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.game = :game')
            ->setParameter('game', $game)
            ->orderBy('r.startedAt', 'ASC')
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
