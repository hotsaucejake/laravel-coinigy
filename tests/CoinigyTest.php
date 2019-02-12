<?php

namespace hotsaucejake\Coinigy\Tests;

use Orchestra\Testbench\TestCase;
use hotsaucejake\Coinigy\Facades\Coinigy;
use hotsaucejake\Coinigy\ServiceProvider;

class CoinigyTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'laravel-coinigy' => Coinigy::class,
        ];
    }

    public function testExample()
    {
        $this->assertEquals(1, 1);
    }
}
