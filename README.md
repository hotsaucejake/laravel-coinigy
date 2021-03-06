# Laravel Coinigy

[![Build Status](https://travis-ci.org/hotsaucejake/laravel-coinigy.svg?branch=master)](https://travis-ci.org/hotsaucejake/laravel-coinigy)
[![styleci](https://styleci.io/repos/170237939/shield)](https://styleci.io/repos/170237939)

[![Release](https://img.shields.io/github/release/hotsaucejake/laravel-coinigy.svg)](https://github.com/hotsaucejake/laravel-coinigy)
[![Packagist](https://poser.pugx.org/hotsaucejake/laravel-coinigy/d/total.svg)](https://packagist.org/packages/hotsaucejake/laravel-coinigy)
[![Packagist](https://img.shields.io/packagist/l/hotsaucejake/laravel-coinigy.svg)](https://packagist.org/packages/hotsaucejake/laravel-coinigy)

[A laravel package for Coinigy API v2](https://api.coinigy.com/api/v2/docs/)

Feel free to submit a PR for missing API calls.  So far, not all of them are included.  You can visit the [Coinigy API v2 docs](https://api.coinigy.com/api/v2/docs/) and view the list of methods below to see what API calls are missing from this package.

## Installation

Install via composer
```bash
composer require hotsaucejake/laravel-coinigy
```

### Register Service Provider and Facade

**Note!  This package has only been tested with Laravel 5.5+, the next section is optional as it includes auto discovery.**
If you're using Laravel <= 5.4 you'll need to:

Add service provider to `config/app.php` in `providers` section
```php
hotsaucejake\Coinigy\ServiceProvider::class,
```

Register package facade in `config/app.php` in `aliases` section
```php
'Coinigy' => hotsaucejake\Coinigy\Facades\Coinigy::class,
```

### Publish Configuration File

```bash
php artisan vendor:publish --provider="hotsaucejake\Coinigy\ServiceProvider" --tag="config"
```

## Usage

```php
use hotsaucejake\Coinigy\Facades\Coinigy;

/*
 ***************************************************************************
 * PUBLIC
 ***************************************************************************
 *
 * Available to anyone, even without a Coinigy Subscription.
 *
 */

// All Blockchains which Coinigy supports.
Coinigy::chains();

// Convert one currency value to any other currency.
Coinigy::convert('BTC', 'ETH');

// All Exchanges listed on Coinigy.
Coinigy::exchanges();

// A specific exchange listed on Coinigy.
Coinigy::exchange('BITS');

// All markets on a given exchange.
Coinigy::exchangeMarkets('BITS')

// All trading pairs listed on Coinigy.
Coinigy::markets();

// Status of Coinigy v2 API.
Coinigy::status();

/*
 ***************************************************************************
 * PRIVATE - Exchanges & Markets
 ***************************************************************************
 *
 * Supported exchanges and trading pairs.
 *
 */

// All Exchanges listed on Coinigy.
Coinigy::getExchanges();

// A specific exchange listed on Coinigy.
Coinigy::getExchange('BITS');

// Info about a given currency.
Coinigy::getCurrency('BITS', 'BTC');

// Trading Pair detail info for a given trading pair.
Coinigy::getExchangeMarket('BITS', 'BTC', 'USD');

// All trading pairs that are no longer actively traded on a given exchange.
Coinigy::getExchangeDeadMarkets('BITS');

// All traiding pairs listed on Coinigy.
Coinigy::getMarkets();

// All trading pairs that are no longer actively traded.
Coinigy::getDeadMarkets();

/*
 ***************************************************************************
 * PRIVATE - Exchange Data
 ***************************************************************************
 *
 * Trade history, order books, market data, news, etc.
 *
 */

// Orderbook depth for a given trading pair.
Coinigy::getOrderBookDepth('BITS', 'BTC', 'USD');

// Price of last trade on a given market.
Coinigy::getLastTrade('BITS', 'BTC', 'USD');

// OHLC candlestick data for a given trading pair and interval.
Coinigy::getCandlestick('BITS', 'BTC', 'USD', 'm', ['StartDate' => '2019-02-11T16:02:38.623Z', 'EndDate' => '2019-02-12T17:02:38.623Z']);

// Historical price ranges for a given trading pair.
Coinigy::getRange('BITS', 'BTC', 'USD');

// 24-Hour Ticker data for a given trading pair.
Coinigy::getTicker('BITS', 'BTC', 'USD');

// Recent trades for a given pair.
Coinigy::getTrades('BITS', 'BTC', 'USD');

// Trade history for a given trading pair.
Coinigy::getTradeHistory('BITS', 'BTC', 'USD', ['StartDate' => '2019-02-12T17:02:38.623Z', 'EndDate' => '2019-02-12T18:02:38.623Z']);

// Trade history for a given trading pair since a given ID.
Coinigy::getTradeHistorySince('BITS', 'BTC', 'USD', $sinceMarketHistoryId);

// 24-Hour Ticker data for all trading pairs on a given exchange.
Coinigy::getExchangeTicker('BITS');

// Ticker data for all trading pairs listed on Coinigy
Coinigy::getMarketTicker();

// Recent articles from Coinigy's news feed
Coinigy::news();

// Search articles from Coinigy's news feed.
Coinigy::newsSearch('bullbearanalytics.com'); // searches titles only

/*
 ***************************************************************************
 * PRIVATE - Price Alerts
 ***************************************************************************
 *
 * Open and triggered price alerts.
 *
 */

// All open price alerts
Coinigy::getAlerts();

// Insert a new price alert.
Coinigy::postAlert('BITS', 'USD/BTC', 100.00);
Coinigy::postAlert('BITS', 'USD/BTC', 100.00, 'Trade Alert!');

// Remove an existing price alert.
Coinigy::deleteAlert(11992352);

// Info about a specific price alert.
Coinigy::getAlert(11992352);

// Change an existing price alert.
// Existing alert will be removed and a new alert for the same Exchange/Market will be added
// The alert sound and alert note will only be updated if provided. Otherwise they will remain the same.
Coinigy::updateAlert(11992352, 200.00);
Coinigy::updateAlert(11992352, 200.00, 'New Alert Note!');

// Remove all open price alerts for a specific market/trading pair.
Coinigy::deletePairAlerts('BTC', 'USD');

// Remove all open price alerts for a specific exchange.
// Coinigy::deleteExchangeAlerts('BITS');
// returns false
// does not work - probably conflicts with deleteAlert() - has the same endpoint
// contact Coinigy about this

// Remove all open price alerts for a specific exchange and market/trading pair.
Coinigy::deleteExchangePairAlerts('BITS', 'BTC', 'USD');

// Previously triggered price alerts.
Coinigy::getAlertHistory();

/*
 ***************************************************************************
 * PRIVATE - Blockchain Data
 ***************************************************************************
 *
 * Supported chains, current metrics and blockchain metric history.
 *
 */

// All blockchains which Coinigy supports.
Coinigy::getChains();

// Blockchain metrics about the most recent block of a specific blockchain.
Coinigy::getChain('BTC');

// Historical blockchain metrics about a specific blockchain.
Coinigy::getChainHistory('BTC', ['StartDate' => '2019-02-10T17:02:38.623Z', 'EndDate' => '2019-02-12T18:02:38.623Z']);


```

## Security

If you discover any security related issues, please email get@hotsaucejake.com
instead of using the issue tracker.

## Credits

- [hotsaucejake](https://github.com/hotsaucejake/laravel-coinigy)
- [All contributors](https://github.com/hotsaucejake/laravel-coinigy/graphs/contributors)
