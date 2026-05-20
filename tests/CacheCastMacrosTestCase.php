<?php

declare(strict_types=1);

namespace Pepperfm\LaravelMacros\Tests;

use Pepperfm\LaravelMacros\Groups\Cache\CacheCastMacros;

class CacheCastMacrosTestCase extends TestCase
{
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('cache.default', 'array');
        $app['config']->set('cache.stores.array', [
            'driver' => 'array',
            'serialize' => false,
        ]);
        $app['config']->set('macros-for-laravel', [
            'enabled' => true,
            'groups' => [
                CacheCastMacros::class,
            ],
        ]);
    }
}
