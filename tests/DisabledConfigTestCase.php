<?php

declare(strict_types=1);

namespace Pepperfm\LaravelMacros\Tests;

use Pepperfm\LaravelMacros\Groups\Support\ArrCastMacros;
use Pepperfm\LaravelMacros\Groups\Support\CollectionMacros;

class DisabledConfigTestCase extends TestCase
{
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('macros-for-laravel', [
            'enabled' => false,
            'groups' => [
                ArrCastMacros::class,
                CollectionMacros::class,
            ],
        ]);
    }
}
