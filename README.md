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

### Register Service Provider

**Note! This and next step are optional if you use laravel>=5.5 with package
auto discovery feature.**

Add service provider to `config/app.php` in `providers` section
```php
hotsaucejake\Coinigy\ServiceProvider::class,
```

### Register Facade

Register package facade in `config/app.php` in `aliases` section
```php
hotsaucejake\Coinigy\Facades\Coinigy::class,
```

### Publish Configuration File

```bash
php artisan vendor:publish --provider="hotsaucejake\Coinigy\ServiceProvider" --tag="config"
```

## Usage

Coming soon

## Security

If you discover any security related issues, please email get@hotsaucejake.com
instead of using the issue tracker.

## Credits

- [hotsaucejake](https://github.com/hotsaucejake/laravel-coinigy)
- [All contributors](https://github.com/hotsaucejake/laravel-coinigy/graphs/contributors)

This package is bootstrapped with the help of
[melihovv/laravel-package-generator](https://github.com/melihovv/laravel-package-generator).
