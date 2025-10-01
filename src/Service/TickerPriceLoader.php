<?php declare(strict_types = 1);

namespace App\Service;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TickerPriceLoader
{
    const REQUEST_URI = '/api/v3/ticker/price';

    public function __construct(
        private LoggerInterface $logger,
        #[Autowire('%price.symbols%')]
        private array           $priceSymbols,
        #[Autowire('%binance.api.hosts%')]
        private array           $hostsPool
    )
    {
    }

    public function loadPrices(): ?string
    {
        $symbols = $this->getSymbolParameter();

        foreach ($this->hostsPool as $host) {
            try {
                $requestUrl = sprintf('%s%s?symbols=%s', $host, self::REQUEST_URI, $symbols);

                $response = HttpClient::create()->request('GET', $requestUrl);

                if ($response->getStatusCode() !== Response::HTTP_OK) {
                    throw new Exception("$requestUrl returned status code " . $response->getStatusCode());
                }

                return $response->getContent();
            } catch (Throwable $e) {
                $this->logger->error("Can't load prices from $host: " . $e->getMessage());
            }
        }

        throw new Exception("Can't load prices from any host");
    }

    private function getSymbolParameter(): string
    {
        return '["' . implode('","', $this->priceSymbols) . '"]';
    }
}
