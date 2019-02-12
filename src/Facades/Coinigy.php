<?php

namespace hotsaucejake\Coinigy\Facades;

use Illuminate\Support\Facades\Facade;

class Coinigy extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-coinigy';
    }
}
