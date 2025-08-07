<?php

namespace App\Repository;

use App\Entity\Contest;
use App\Entity\Game;
use App\Entity\Question;
use App\Entity\Round;
use App\Enum\Difficulty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Question>
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    public function findQuestionByContestAndGameAndDifficulty(Contest $contest, Game $game, Difficulty $difficulty): ?Question
    {
        $usedQuestionIds = array_map(
            fn (Round $round) => $round->getQuestion(),
            $game->getRounds()->toArray()
        );

        return $this->createQueryBuilder('q')
            ->where('q.contest = :contest')
            ->andWhere('q.id NOT IN (:usedIds)')
            ->andWhere('q.difficulty = (:difficulty)')
            ->setParameter('contest', $contest)
            ->setParameter('usedIds', $usedQuestionIds ?: [Uuid::v4()])
            ->setParameter('difficulty', $difficulty->value)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    //    /**
    //     * @return Question[] Returns an array of Question objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('q.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Question
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
