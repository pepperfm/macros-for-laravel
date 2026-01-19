<?php

declare(strict_types=1);

namespace Pepperfm\LaravelMacros\Tests;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Orchestra\Testbench\TestCase as Orchestra;
use Pepperfm\LaravelMacros\Groups\Support\ArrCastMacros;
use Pepperfm\LaravelMacros\Groups\Support\CollectionMacros;
use Pepperfm\LaravelMacros\Providers\LaravelMacrosServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        Arr::flushMacros();
        Collection::flushMacros();

        parent::setUp();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [LaravelMacrosServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('macros-for-laravel', [
            'enabled' => true,
            'groups' => [
                ArrCastMacros::class,
                CollectionMacros::class,
            ],
        ]);
    }
}
