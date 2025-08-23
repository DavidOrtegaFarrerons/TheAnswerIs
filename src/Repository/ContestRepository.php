<?php

namespace App\Repository;

use App\Entity\Contest;
use App\Enum\ContestStatus;
use App\Enum\ContestVisibility;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contest>
 */
class ContestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contest::class);
    }

    public function getLatestContests(int $contestsNumber)
    {
        return $this->createQueryBuilder('c')
            ->where('c.status = :published')
            ->andWhere('c.visibility = :public')
            ->setParameter('published', ContestStatus::PUBLISHED->value)
            ->setParameter('public', ContestVisibility::PUBLIC->value)
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults($contestsNumber)
            ->getQuery()
            ->getResult()
        ;
    }

    //    /**
    //     * @return Contest[] Returns an array of Contest objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Contest
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
