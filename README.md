Run:<br>
``cp env-example .env``<br>
``cd docker && docker-compose up -d``<br>
``docker exec b-price-php bash -c "composer install"``<br>
``docker exec b-price-php bash -c "bin/console doctrine:migrations:migrate"``<br>
<br>
Start cron service:<br>
``docker exec b-price-php bash -c "cron"``

Symbols to load and api hosts can be configured in:<br>
``config/services.yaml``
```
price.symbols:
    - 'BTCEUR'
    - 'ETHEUR'
    - 'LTCEUR'
binance.api.hosts:
    - 'https://api4.binance.com'
    - 'https://api3.binance.com'
    - 'https://api2.binance.com'
    - 'https://api1.binance.com'
    - 'https://api4.binance.com'
    - 'https://api.binance.com'
    - 'https://api-gcp.binance.com'
```
Load interval can be configured in ``config/services.yaml``<br>

Requests example:<br>
``http://localhost/api/rates/last-24h?pair=EUR/BTC``<br>
``http://localhost/api/rates/day?pair=EUR/BTC&date=2025-09-30``
