<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\RateHistory;
use DateTime;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class RateHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RateHistory::class);
    }

    /**
     * @return RateHistory[]
     */
    public function findByPairLast24h(string $currencyFrom, string $currencyTo): array
    {
        return $this->findByPairQb($currencyFrom, $currencyTo)
            ->andWhere('r.date > :date')
            ->setParameter('date', (new DateTime())->modify('-1 day')->format(DateTimeInterface::ATOM))
            ->getQuery()
            ->getResult();
    }

    private function findByPairQb(string $currencyFrom, string $currencyTo): QueryBuilder
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.currency_from = :from')
            ->andWhere('r.currency_to = :to')
            ->setParameter('from', $currencyFrom)
            ->setParameter('to', $currencyTo)
            ->orderBy('r.date', 'DESC');
    }

    /**
     * @return RateHistory[]
     */
    public function findByPairDay(string $currencyFrom, string $currencyTo, DateTime $dateTime): array
    {
        return $this->findByPairQb($currencyFrom, $currencyTo)
            ->andWhere('r.date > :date_from')
            ->andWhere('r.date < :date_to')
            ->setParameter('date_from', $dateTime->format(DateTimeInterface::ATOM))
            ->setParameter('date_to', $dateTime->modify('+1 day')->format(DateTimeInterface::ATOM))
            ->getQuery()
            ->getResult();
    }
}
