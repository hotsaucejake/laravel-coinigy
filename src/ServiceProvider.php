<?php

namespace hotsaucejake\Coinigy;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__.'/../config/laravel-coinigy.php';

    public function boot()
    {
        $this->publishes([
            self::CONFIG_PATH => config_path('laravel-coinigy.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'laravel-coinigy'
        );

        $this->app->bind('laravel-coinigy', function () {
            return new Coinigy();
        });
    }
}
