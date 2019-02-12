# Laravel Coinigy

[![Build Status](https://travis-ci.org/hotsaucejake/laravel-coinigy.svg?branch=master)](https://travis-ci.org/hotsaucejake/laravel-coinigy)
[![styleci](https://styleci.io/repos/170237939/shield)](https://styleci.io/repos/170237939)

[![Packagist](https://img.shields.io/packagist/v/hotsaucejake/laravel-coinigy.svg)](https://packagist.org/packages/hotsaucejake/laravel-coinigy)
[![Packagist](https://poser.pugx.org/hotsaucejake/laravel-coinigy/d/total.svg)](https://packagist.org/packages/hotsaucejake/laravel-coinigy)
[![Packagist](https://img.shields.io/packagist/l/hotsaucejake/laravel-coinigy.svg)](https://packagist.org/packages/hotsaucejake/laravel-coinigy)

Package description: A laravel package for Coinigy API v2

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

```

## Security

If you discover any security related issues, please email get@hotsaucejake.com
instead of using the issue tracker.

## Credits

- [hotsaucejake](https://github.com/hotsaucejake/laravel-coinigy)
- [All contributors](https://github.com/hotsaucejake/laravel-coinigy/graphs/contributors)
