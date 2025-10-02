<?php declare(strict_types = 1);

namespace App\Command;

use App\DTO\ExchangePair;
use App\Service\TickerPriceLoader;
use App\Service\RateHistorySaver;
use DateTime;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

#[AsCommand(name: 'app:prices:load')]
class PricesLoadCommand
{
    public function __construct(
        private RateHistorySaver    $rateHistoryManager,
        private SerializerInterface $serializer,
        private LoggerInterface     $logger,
        private TickerPriceLoader   $dataLoader
    )
    {
    }

    public function __invoke(): int
    {
        try {
            $pricesData = $this->dataLoader->loadPrices();
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage());

            return Command::FAILURE;
        }

        $loadDateTime = (new DateTime());
        $prices       = $this->serializer->deserialize($pricesData, 'App\DTO\Price[]', 'json');

        foreach ($prices as $price) {
            $currencyFrom = substr($price->getSymbol(), 0, ExchangePair::CURRENCY_CODE_LEN);
            $currencyTo   = substr($price->getSymbol(), ExchangePair::CURRENCY_CODE_LEN, ExchangePair::CURRENCY_CODE_LEN * 2);

            $this->rateHistoryManager->addRate(
                $currencyTo,
                $currencyFrom,
                $this->flipRate($price->getPrice()),
                $loadDateTime
            );
        }

        $this->rateHistoryManager->save();

        return Command::SUCCESS;
    }

    private function flipRate(string $rate): string
    {
        return bcdiv('1', $rate, 8);
    }
}
