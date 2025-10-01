<?php declare(strict_types = 1);

namespace App\Entity;

use App\Repository\RateHistoryRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RateHistoryRepository::class)]
class RateHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 3)]
    private ?string $currency_from = null;

    #[ORM\Column(length: 3)]
    private ?string $currency_to = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 65, scale: 8)]
    private ?string $rate = null;

    #[ORM\Column]
    private ?DateTime $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrencyFrom(): ?string
    {
        return $this->currency_from;
    }

    public function setCurrencyFrom(string $currency_from): static
    {
        $this->currency_from = $currency_from;

        return $this;
    }

    public function getCurrencyTo(): ?string
    {
        return $this->currency_to;
    }

    public function setCurrencyTo(?string $currency_to): static
    {
        $this->currency_to = $currency_to;

        return $this;
    }

    public function getRate(): ?string
    {
        return $this->rate;
    }

    public function setRate(string $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }
}
