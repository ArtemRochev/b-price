<?php declare(strict_types = 1);

namespace App\Service;

use App\Entity\RateHistory;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class RateHistorySaver
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function addRate(string $currencyFrom, string $currencyTo, string $rate, DateTime $date): void
    {
        $rateHistory = new RateHistory();

        $rateHistory->setCurrencyFrom($currencyFrom);
        $rateHistory->setCurrencyTo($currencyTo);
        $rateHistory->setRate($rate);
        $rateHistory->setDate($date);

        $this->em->persist($rateHistory);
    }

    public function save(): void
    {
        $this->em->flush();
    }
}
