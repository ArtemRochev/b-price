<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\RateHistory;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RateHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RateHistory::class);
    }

    /**
     * @return RateHistory[] Returns an array of RateHistory objects
     */
    public function findByPair(string $from, string $to): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.currency_from = :from')
            ->andWhere('r.currency_to = :to')
            ->andWhere('r.date > :date')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->setParameter('date', (new DateTime())->format('Y-m-d'))
            ->orderBy('r.date', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?RateHistory
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
